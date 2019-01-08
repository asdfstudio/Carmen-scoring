import Vue from 'vue'
import Vuex from 'vuex'
import {captions} from './captions'
import {divisions} from './divisions'
import {choirs} from './choirs'
import {criteria} from './criteria'
import {scores} from './scores'
import {comments} from './comments'
import CommentsApi from '../api/comments'
import ScoresApi from '../api/scores'
import _ from 'lodash'

Vue.use(Vuex)

// Debounced API calls
const saveComment = _.debounce(CommentsApi.saveComment, 1000)
// const saveScore = _.debounce(ScoresApi.saveScore, 1000)

// Not totall working yet
// See https://stackoverflow.com/questions/28787436/debounce-a-function-with-argument
var saveDebouncedScore = _.wrap(_.memoize(function () {
  return _.debounce(ScoresApi.saveScore, 1000)
}, _.property(['choir_id', 'criterion_id'])), function (func, obj) {
  return func(obj)(obj)
})

export const store = new Vuex.Store({
  state: {
    count: 0,
    scoringStatus: 'Active',
    captionsList: captions,
    divisions: divisions,
    choirsList: choirs,
    criteriaList: criteria,
    scores: scores,
    comments: comments,
    activeModal: false,
    activeCriterion: false,
    activeChoir: false,
    activeComment: false
  },
  mutations: {
    activateModal (state, data) {
      state.activeModal = true
      console.log(data)
    },
    activateChoirCommentModal (state, choir) {
      state.activeModal = true
      state.activeComment = true
      state.activeChoir = choir
      state.activeCriterion = false
    },
    activateChoirModal (state, choir) {
      state.activeModal = true
      state.activeComment = false
      state.activeChoir = choir
      state.activeCriterion = false
    },
    activateCriterionModal (state, criterion) {
      state.activeModal = true
      state.activeComment = false
      state.activeChoir = false
      state.activeCriterion = criterion
    },
    activateChoirCriterionModal (state, payload) {
      state.activeModal = true
      state.activeComment = false
      state.activeChoir = payload.choir
      state.activeCriterion = payload.criterion
    },
    deactivateModal (state) {
      state.activeModal = false
    },
    activateChoir (state, choir) {
      state.activeChoir = choir
    },
    activateCriterion (state, criterion) {
      state.activeCriterion = criterion
    },
    setScore (state, payload) {
      // Find the matching score and update it
      var matches = state.scores.filter(score => score.choir_id === payload.choir_id).filter(score => score.criterion_id === payload.criterion_id)

      if (matches.length === 1) {
        matches[0].raw_score = payload.raw_score
      } else {
        state.scores.push(payload)
      }
      // Otherwise append it to the array
    },
    setComment (state, payload) {
      // Find the matching score and update it
      var matches = state.comments.filter(comment => comment.choir_id === payload.choir_id)

      if (matches.length === 1) {
        matches[0].comment = payload.comment
      } else {
        state.comments.push(payload)
      }
      // Otherwise append it to the array
    }
  },
  actions: {
    setScore (context, payload) {
      // Send to mutation
      store.commit('setScore', payload)

      // Send ajax request, use debounce
      // saveScore(payload)
      saveDebouncedScore(payload)
    },
    setComment (context, payload) {
      // Send to mutation
      store.commit('setComment', payload)

      // Send ajax request, use debounce
      saveComment(payload)
    }
  },
  getters: {
    getCount: (state) => {
      return state.count
    },
    getChoirScores: (state) => (choirId) => {
      return state.scores.filter(score => score.choir_id === choirId)
    },
    getChoirTotalScore: (state, getters) => (choirId) => {
      var scores = getters.getChoirScores(choirId)
      return getters.sumScores(scores)
    },
    getChoirCaptionSubtotalScore: (state, getters) => (choirId, captionId) => {
      var scores = getters.getChoirScores(choirId)
      // Filter by caption
      return getters.sumScores(scores.filter(score => score.caption_id === captionId))
    },
    sumScores: (state) => (scoreItems) => {
      return scoreItems.reduce(function (previousValue, item) {
        return previousValue + item.raw_score
      }, 0)
    },
    getCriterionScores: (state) => (criterionId) => {
      return state.scores.filter(score => score.criterion_id === criterionId)
    },
    activeCriterion: (state) => {
      return state.activeCriterion
    },
    activeChoir: (state) => {
      return state.activeChoir
    },
    activeChoirScores: (state) => {
      return state.scores.filter(score => score.choir_id === state.activeChoir.id)
    },
    activeCriterionScores: (state) => {
      return state.scores.filter(score => score.criterion_id === state.activeCriterion.id)
    },
    getChoirCriterionScore: (state) => (choirId, criterionId) => {
      var matches = state.scores.filter(score => score.choir_id === choirId).filter(score => score.criterion_id === criterionId)

      if (matches.length === 1) return matches[0].raw_score

      return null
    },
    getChoirComment: (state) => (choirId) => {
      var matches = state.comments.filter(comment => comment.choir_id === choirId)

      if (matches.length === 1) return matches[0].comment

      return null
    }
  }
})
