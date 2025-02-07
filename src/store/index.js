import Vue from 'vue'
import Vuex from 'vuex'
import CommentsApi from '../api/comments'
import RecordingApi from '../api/recordings'
import ScoresApi from '../api/scores'
import _ from 'lodash'
import axios from 'axios'
import ai_comments_view from '../api/ai_comments_view'

let apiUrl = window.__API_URL__;
let backUrl = window.__BACK_URL__;

Vue.use(Vuex)

// Debounced API calls
const saveRecording = RecordingApi.saveRecording

// See https://stackoverflow.com/questions/28787436/debounce-a-function-with-argument
var saveDebouncedScore = _.wrap(
  _.memoize(
    function () {
      return _.debounce(ScoresApi.saveScore, 500)
    },
    _.property(
      [
        'choir_id',
        'criterion_id'
      ]
    )
  ),
  function (func, obj, store) {
    return func(obj, store)(obj, store)
  }
)

var setComment = _.debounce(async function (payload) {
    // Send ajax request
    const data = await CommentsApi.saveComment(payload)

    // Send to mutation
    store.commit('setComment', {
        choir_id: data.choir_id,
        comment: data.comments,
        ai_comment: data.ai_comments,
        recipient_id: data.recipient_id,
        recipient_type: data.recipient_type
    });
}, 1000);

var setAIComment = _.debounce(async function (payload) {
  // Send ajax request
  const data = await CommentsApi.saveComment(payload)

  // Send to mutation
  store.commit('setAIComment', {
      choir_id: data.choir_id,
      comment: data.comments,
      ai_comment: data.ai_comments,
      recipient_id: data.recipient_id,
      recipient_type: data.recipient_type
  });
}, 1000);

var setAICommentView = _.debounce(async function (payload) {
  // Check if AI comment already exists before making an API call
  if (store.getters.getChoirAICommentView(payload.choir_id)) {
    return;
  }

  // Set loading before the request starts
  store.commit("SET_LOADING_STATE", { choirId: payload.choir_id, loading: true });

  try {
    // Send AJAX request
    const data = await ai_comments_view.viewAIComment(payload);

    // Send result to Vuex store
    store.commit("setAICommentView", {
      choir_id: data.choir_id,
      ai_comment_view: data.ai_comments_view,
      recipient_id: data.recipient_id,
      recipient_type: data.recipient_type
    });

  } catch (error) {
    console.error("Error fetching AI comments:", error);
    store.commit("setAICommentView", {
      choir_id: payload.choir_id,
      ai_comment_view: "Error loading AI comment"
    });

  } finally {
    // Set loading to false after request completes
    store.commit("SET_LOADING_STATE", { choirId: payload.choir_id, loading: false });
  }
}, 1);
 // Reduced debounce time to 500ms for a better user experience.


export const store = new Vuex.Store({
  state: {
    isSpreadsheetScoringActive: false,
    captionsList: [],
    captionWeightingId: -1,
    divisions: [],
    recordings: [],
    competition: {},
    choirsList: [],
    criteriaList: [],
    scores: [],
    saving: {},
    saved: {},
    errored: {},
    comments: {},
    hasRatings: false,
    activeModal: false,
    protectModal: false,
    activeCriterion: false,
    activeChoir: false,
    activeComment: false,
    activeChoirCriterionComment:false,
    spreadsheetTitle: 'Default Spreadsheet Title',
    backUrl: backUrl,
    apiUrl: apiUrl,
    aiCommentsActive: false,
    aiCommentsViewActive: false,
    CommentsActive: false,
    loadingAIComments: {}
  },
  mutations: {
    activateModal (state, data) {
      state.activeModal = true
    },
    startModalProtection (state, data) {
      state.protectModal = true
    },
    activateChoirCommentModal (state, choir) {
      state.activeModal = true
      state.activeComment = true
      state.activeChoir = choir
      state.activeCriterion = false
      state.activeChoirCriterionComment = false
      state.aiCommentsActive = false
      state.aiCommentsViewActive = false
      state.CommentsActive = true
    },
    activateChoirAICommentModal (state, choir) {
      state.activeModal = true
      state.activeComment = true
      state.activeChoir = choir
      state.activeCriterion = false
      state.activeChoirCriterionComment = false
      state.CommentsActive = false
      state.aiCommentsViewActive = false
      state.aiCommentsActive = true
    },
    activateChoirAICommentViewModal (state, choir) {
      state.activeModal = true
      state.activeComment = true
      state.activeChoir = choir
      state.activeCriterion = false
      state.activeChoirCriterionComment = false
      state.CommentsActive = false
      state.aiCommentsActive = false
      state.aiCommentsViewActive = true
    },
    activateChoirModal (state, choir) {
      state.activeModal = true
      state.activeComment = false
      state.activeChoirCriterionComment = false
      state.activeChoir = choir
      state.activeCriterion = false
      state.CommentsActive = false
      state.aiCommentsActive = false
      state.aiCommentsViewActive = false
    },
    activateChoirCriterionModal (state, payload) {
      state.activeModal = true
      state.activeComment = false
      state.activeChoirCriterionComment = false
      state.activeChoir = payload.choir
      state.activeCriterion = payload.criterion
      state.CommentsActive = false
      state.aiCommentsActive = false
      state.aiCommentsViewActive = false
    },
    activateChoirCriterionCommentModal (state, payload) {
        state.activeModal = true
        state.activeChoirCriterionComment = payload
        state.activeChoir = false
        state.activeCriterion = false
    },
    deactivateModal (state) {
      state.activeModal = false
    },
    endModalProtection (state, data) {
      state.protectModal = false
    },
    activateChoir (state, choir) {
      state.activeChoir = choir
    },
    activateCriterion (state, criterion) {
      state.activeCriterion = criterion
    },
    updateChoirsList (state, payload) {
      state.choirsList = payload
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
      // Find the matching comment and update it
      // var matches = state.comments.filter(comment => comment.choir_id === payload.choir_id)
      for (var c in state.comments) {
        const isChoirCriterionComment = state.comments[c].choir_id === payload.choir_id
                && state.comments[c].recipient_id === payload.recipient_id
                && state.comments[c].recipient_type === payload.recipient_type;
        if (isChoirCriterionComment) {
          state.comments[c].comment = payload.comment
          return
        }
      }
      const keys = Object.keys(state.comments);
      const index = keys.pop() || 0;

      // Otherwise append it to the array
      state.comments[+index + 1] = payload;
    },
    setAIComment (state, payload) { 
      // Find the matching comment and update it
      // var matches = state.comments.filter(comment => comment.choir_id === payload.choir_id)
      for (var c in state.comments) {
        const isChoirCriterionComment = state.comments[c].choir_id === payload.choir_id
                && state.comments[c].recipient_id === payload.recipient_id
                && state.comments[c].recipient_type === payload.recipient_type;
        if (isChoirCriterionComment) {
          state.comments[c].ai_comment = payload.ai_comment
          return
        }
      }

      const keys = Object.keys(state.comments);
      const index = keys.pop() || 0;

      // Otherwise append it to the array
      state.comments[+index + 1] = payload;
    },
    setAICommentView (state, payload) { 
      // Find the matching comment and update it
      // var matches = state.comments.filter(comment => comment.choir_id === payload.choir_id)
      for (var c in state.comments) {
        const isChoirCriterionComment = state.comments[c].choir_id === payload.choir_id
                && state.comments[c].recipient_id === payload.recipient_id
                && state.comments[c].recipient_type === payload.recipient_type;
        if (isChoirCriterionComment) {
          state.comments[c].ai_comment_view = payload.ai_comment_view
          return
        }
      }

      const keys = Object.keys(state.comments);
      const index = keys.pop() || 0;

      // Otherwise append it to the array
      state.comments[+index + 1] = payload;
    },
    SET_LOADING_STATE(state, { choirId, loading }) {
      state.loadingAIComments = { ...state.loadingAIComments, [choirId]: loading };
    },
    setSavingStatus (state, statusObj) {
      state.saving = Object.assign({}, state.saving, statusObj)
    },
    setSavedStatus (state, statusObj) {
      state.saved = Object.assign({}, state.saved, statusObj)
    },
    setErroredStatus (state, statusObj) {
      state.errored = Object.assign({}, state.errored, statusObj)
    },
    setHasRatings (state) {
      state.hasRatings = state.divisions.some(function(division) {
        return division.rating_system != null && division.rating_system.length > 0
      })
    },
    setApiData (state, payload) {
      state.captionWeightingId = payload.captionWeightingId
      state.captionsList = payload.captions
      state.divisions = payload.divisions
      state.choirsList = payload.choirs
      state.criteriaList = payload.criteria
      state.scores = payload.scores
      state.comments = payload.comments
      state.recordings = payload.recordings
      state.competition = payload.competition
      state.spreadsheetTitle = payload.spreadsheetTitle
      state.backUrl = payload.backUrl
      state.isSpreadsheetScoringActive = payload.isSpreadsheetScoringActive
    }
  },
  actions: {
    async getApiData(context) {
      const { data } = await axios.get(store.state.apiUrl)
      context.commit('setApiData', data)
      context.commit('setHasRatings')
    },
    setScore (context, payload) {
      // Find the matching choir
      var matches = store.state.choirsList.filter(choir => choir.id === payload.choir_id)

      var choirDetails = matches[0]
      var uniqueKey = payload.choir_id + '_' + payload.caption_id + '_' + payload.criterion_id

      // Use the additional details of the choir in the payload
      if (choirDetails) {
        payload.round_id = choirDetails.round_id
        payload.division_id = choirDetails.division_id
      }

      // Send to mutation
      store.commit('setScore', payload)
      store.commit('setSavingStatus', {[uniqueKey]: true})
      store.commit('setSavedStatus', {[uniqueKey]: false})
      store.commit('setErroredStatus', {[uniqueKey]: false})

      // Send ajax request, use debounce
      // console.log('Calling saveDebouncedScore(payload) where payload is:\n', payload)
      saveDebouncedScore(payload, store)
    },
    async setComment(context, payload) {
        setComment(payload)
    },
    async setAIComment(context, payload) {
        setAIComment(payload)
    },
    async setAICommentView(context, payload) {
      setAICommentView(payload)
  },
    saveRecording (context, payload) {
      // Send ajax request
      saveRecording(payload)
    }
  },
  getters: {
    captionWeightingId: (state) => {
      return state.captionWeightingId
    },
    getChoirsList: (state) => {
      return state.choirsList.slice(0).sort(function (a, b) {
        if (typeof a.performance_order === 'undefined' || typeof a.performance_order === 'undefined') {
          return a.name.localeCompare(b.name)
        } else {
          var performanceOrderDifference = a.performance_order - b.performance_order
          if (performanceOrderDifference === 0) {
            return a.name.localeCompare(b.name)
          }
          return performanceOrderDifference
        }
      })
    },
    getChoirScores: (state) => (choirId) => {
      return state.scores.filter(score => score.choir_id === choirId)
    },
    getChoirTotalScore: (state, getters) => (choirId) => {
      var scores = getters.getChoirScores(choirId)
      return getters.sumScores(scores)
    },
    getChoirTotalWeightedScore: (state, getters) => (choirId) => {
      var scores = getters.getChoirScores(choirId)
      return getters.sumWeightedScores(scores)
    },
    updateChoirsRanks: (state, getters) => {
      var choirs = state.choirsList
      for (var i = 0; i < choirs.length; i++) {
        choirs[i].total_score = getters.getChoirTotalWeightedScore(choirs[i].id)
      }
      choirs.sort(function (a, b) {
        return b.total_score - a.total_score
      })
      var rank = 1
      for (var j = 0; j < choirs.length; j++) {
        choirs[j].rank = rank
        choirs[j].rank_tied = false
        if ((j > 0 && choirs[j].total_score === choirs[j - 1].total_score) || (j + 1 < choirs.length && choirs[j].total_score === choirs[j + 1].total_score)) {
          // If the previous or following choir has the same score, note them as "Tied".
          choirs[j].rank_tied = true
        }
        if (j + 1 < choirs.length && choirs[j].total_score !== choirs[j + 1].total_score) {
          // If the following choir does not have the same schore, increment the rank.
          rank++
        }
      }
      // return choirs.sort(function (a, b) {
      //   if (typeof a.performance_order === 'undefined' || typeof a.performance_order === 'undefined') {
      //     return a.name.localeCompare(b.name)
      //   } else {
      //     var performanceOrderDifference = a.performance_order - b.performance_order
      //     if (performanceOrderDifference === 0) {
      //       return a.name.localeCompare(b.name)
      //     }
      //     return performanceOrderDifference
      //   }
      // })
    },
    getChoirRating: (state, getters) => (choir, score) => {
      var percentage = score === 0 ? 0 : Math.round(score / getters.maxScore * 100)
      var highestRatingMinScore = 0
      var ratingName = 'No Rating'
      var division = state.divisions.find(function(division) {
          return division.id == choir.division_id;
      });
      if (division?.rating_system) {
          for (let rating of division.rating_system) {
            if (percentage >= rating.min_score && highestRatingMinScore < rating.min_score) {
              highestRatingMinScore = rating.min_score
              ratingName = rating.name
            }
          }
          return ratingName + ' (' + percentage + '%)'
      } else {
        return "Class Not Rated"
      }
    },
    getChoirCaptionRank: (state, getters) => (choirId, captionId) => {
      var choirs = state.choirsList
      var choirCaptionScores = []
      // Loop through the choirs and get the caption score for each one.
      for (var i = 0; i < choirs.length; i++) {
        choirCaptionScores[i] = {
          choir_id: choirs[i].id,
          caption_score: getters.getChoirCaptionSubtotalScore(choirs[i].id, captionId)
        }
      }
      // Sort the list of captions scores.
      choirCaptionScores.sort(function (a, b) {
        return b.caption_score - a.caption_score
      })
      // Assign a rank to each score.
      var rank = 1
      for (var j = 0; j < choirCaptionScores.length; j++) {
        choirCaptionScores[j].rank = rank
        choirCaptionScores[j].rank_tied = false
        if ((j > 0 && choirCaptionScores[j].caption_score === choirCaptionScores[j - 1].caption_score) || (j + 1 < choirCaptionScores.length && choirCaptionScores[j].caption_score === choirCaptionScores[j + 1].caption_score)) {
          // If the previous or following choir has the same score, note them as "Tied".
          choirCaptionScores[j].rank_tied = true
        }
        // If the current item in the loop belongs the choir we're trying to look up, then go ahead and return it.
        if (choirCaptionScores[j].choir_id === choirId) {
          return choirCaptionScores[j]
        }
        if (j + 1 < choirCaptionScores.length && choirCaptionScores[j].caption_score !== choirCaptionScores[j + 1].caption_score) {
          // If the following choir does not have the same schore, increment the rank.
          rank++
        }
      }
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
    sumWeightedScores: (state) => (scoreItems) => {
      return scoreItems.reduce(function (previousValue, item) {
        var scoreToAdd = item.raw_score
        // If this division is using weighted scores for the music caption, calculate the weighte score here.
        if (state.captionWeightingId === 1 && item.caption_id === 1) {
          scoreToAdd = scoreToAdd * 1.5
        }
        return previousValue + scoreToAdd
      }, 0)
    },
    maxScore: (state) => {
      var maxTotalScore = 0
      for (var i = 0; i < state.criteriaList.length; i++) {
        var criteriaMaxScore = state.criteriaList[i].maxScore
        if (state.captionWeightingId === 1 && state.criteriaList[i].caption_id === 1) {
          criteriaMaxScore = criteriaMaxScore * 1.5
        }
        maxTotalScore += criteriaMaxScore
      }
      return maxTotalScore
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
      for (var c in state.comments) {
        if (state.comments[c].choir_id === choirId) {
          return state.comments[c].comment
        }
      }
      return null
    },
    getChoirAIComment: (state) => (choirId) => {
      for (var c in state.comments) {
        if (state.comments[c].choir_id === choirId) {
          return state.comments[c].ai_comment
        }
      }
      return null
    },
    getChoirAICommentView: (state) => (choirId) => {
      for (var c in state.comments) {
        if (state.comments[c].choir_id === choirId) {
          return state.comments[c].ai_comment_view
        }
      }
      return null
    },
    isAICommentLoading: (state) => (choirId) => state.loadingAIComments[choirId] || false,
    getChoirCriterionComment: (state) => (choirId, criterionId) => {
        for (var c in state.comments) {
            const isChoirCriterionComment = state.comments[c].choir_id === choirId
                && state.comments[c].recipient_id === criterionId
                && state.comments[c].recipient_type === 'App\\Criterion';
            if (isChoirCriterionComment) {
                return state.comments[c].comment
            }
        }
        return null
    },
    getSavingStatus: (state) => (property) => {
      return (typeof state.saving[property] !== 'undefined' && state.saving[property])
    },
    getSavedStatus: (state) => (property) => {
      return (typeof state.saved[property] !== 'undefined' && state.saved[property])
    },
    getErroredStatus: (state) => (property) => {
      return (typeof state.errored[property] !== 'undefined' && state.errored[property])
    }
  }
})

Vue.mixin({
  methods: {
    toOrdinal: n => {
      // Add a number method that converts the number to an ordinal (or return the value if an ordinal doesn't make sense).
      // This is used, for example, to display choir rank in the judge's spreadsheet view.
      if ((parseFloat(n) === parseInt(n)) && !isNaN(n)) {
        var s = ['th', 'st', 'nd', 'rd']
        var v = n % 100
        return n + (s[(v - 20) % 10] || s[v] || s[0])
      }
      return n
    }

  }
})
