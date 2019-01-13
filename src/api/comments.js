import axios from 'axios'

export default {
  saveComment (payload) {
    const postPayload = {
      round_id: payload.round_id,
      choir_id: payload.choir_id,
      comment: payload.comment
    }
    console.log(payload)

    return axios.post('/judge/comment/save', postPayload)
      .then(response => {
        return response.data
      })
  }
}
