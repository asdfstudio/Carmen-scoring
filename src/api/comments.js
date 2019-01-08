import axios from 'axios'

export default {
  saveComment (payload) {
    const postPayload = {
      choir_id: payload.choir_id,
      comment: payload.comment
    }
    console.log('save comment')
    console.log(payload)

    return axios.post('https://reqres.in/api/comments', postPayload)
      .then(response => {
        return response.data
      })
  }
}
