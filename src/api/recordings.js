import axios from 'axios'

export default {
  saveRecording (payload) {
    return axios.post('/judge/recording/save', payload)
      .then(response => {
        return response.data
      })
  }
}
