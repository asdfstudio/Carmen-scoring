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

let captionsList = window.__CAPTIONS__ ? window.__CAPTIONS__ : captions
let divisionsList = window.__DIVISIONS__ ? window.__DIVISIONS__ : divisions
let choirsList = window.__CHOIRS__ ? window.__CHOIRS__ : choirs
let criteriaList = window.__CRITERIA__ ? window.__CRITERIA__ : criteria
let scoresList = window.__SCORES__ ? window.__SCORES__ : scores
let commentsList = window.__COMMENTS__ ? window.__COMMENTS__ : comments

let spreadsheetTitle = window.__SPREADSHEET_TITLE__ ? window.__SPREADSHEET_TITLE__ : 'Spreadsheet title'
let backUrl = window.__BACK_URL__ ? window.__BACK_URL__ : '/test-back-url'
let isSpreadsheetScoringActive = window.__IS_SPREADSHEET_SCORING_ACTIVE__ === 'Active'
// let isSpreadsheetScoringActive = true

Vue.use(Vuex)

// Debounced API calls
const saveComment = _.debounce(CommentsApi.saveComment, 1000)
// const saveScore = _.debounce(ScoresApi.saveScore, 1000)

// Not totall working yet
// See https://stackoverflow.com/questions/28787436/debounce-a-function-with-argument
var saveDebouncedScore = _.wrap(_.memoize(function () {
  return _.debounce(ScoresApi.saveScore, 500)
}, _.property(['choir_id', 'criterion_id'])), function (func, obj) {
  return func(obj)(obj)
})

export const store = new Vuex.Store({
  state: {
    count: 0,
    isSpreadsheetScoringActive: isSpreadsheetScoringActive,
    // scoringStatus: 'Active',
    captionsList: captionsList,
    divisions: divisionsList,
    choirsList: choirsList,
    criteriaList: criteriaList,
    scores: scoresList,
    comments: commentsList,
    activeModal: false,
    activeCriterion: false,
    activeChoir: false,
    activeComment: false,
    spreadsheetTitle: spreadsheetTitle,
    backUrl: backUrl
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
      // Find the matching choir
      var matches = store.state.choirsList.filter(choir => choir.id === payload.choir_id)

      var choirDetails = matches[0]

      // Use the additional details of the choir in the payload
      if (choirDetails) {
        payload.round_id = choirDetails.round_id
        payload.division_id = choirDetails.division_id
      }

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
    getChoirsList: (state) => {
      return state.choirsList.slice(0).sort(function (a, b) {
        return a.performance_order - b.performance_order
      })
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
