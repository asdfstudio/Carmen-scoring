import axios from 'axios'

export default {
  saveScore (payload) {
    const postPayload = {
      choir_id: payload.choir_id,
      criterion_id: payload.criterion_id,
      score: payload.raw_score,
      round_id: payload.round_id,
      division_id: payload.division_id
    }
    return axios.post('/judge/score/save', postPayload)
      .then(response => {
        return response.data
      })
  }
}
