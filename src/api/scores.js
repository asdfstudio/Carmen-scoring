import axios from 'axios'

export default {
  saveScore (payload) {
    const postPayload = {
      choir_id: payload.choir_id,
      criterion_id: payload.criterion_id,
      raw_score: payload.raw_score
    }

    console.log('save score')
    console.log(postPayload)

    return axios.post('https://reqres.in/api/scores', postPayload)
      .then(response => {
        return response.data
      })
  }
}
