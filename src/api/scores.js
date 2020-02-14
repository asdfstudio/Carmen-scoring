import axios from 'axios'

export default {
  saveScore (payload, store) {
    store = typeof store === 'undefined' ? null : store

    const postPayload = {
      choir_id: payload.choir_id,
      criterion_id: payload.criterion_id,
      score: payload.raw_score,
      round_id: payload.round_id,
      division_id: payload.division_id
    }
    return axios.post('/judge/score/save', postPayload)
      .then(
        response => {
          var uniqueKey = payload.choir_id + '_' + payload.caption_id + '_' + payload.criterion_id
          if(store){
            store.commit('setSavingStatus', {[uniqueKey]: false})
            if(response.data.success){
              store.commit('setSavedStatus', {[uniqueKey]: true})
            } else {
              store.commit('setErroredStatus', {[uniqueKey]: true})
            }
          }
          return response.data
        },
        response => {
          var uniqueKey = payload.choir_id + '_' + payload.caption_id + '_' + payload.criterion_id
          store.commit('setErroredStatus', {[uniqueKey]: true})
          return response.data
        }
      )
  }
}
