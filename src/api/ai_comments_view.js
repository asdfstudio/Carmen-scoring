import axios from 'axios'

export default {
  viewAIComment (payload) {
    const postPayload = {
      round_id: payload.round_id,
      choir_id: payload.choir_id,
      criteria_id: payload.criteria_id,
      ai_comment_view: payload.ai_comment_view
    }
    return axios.post('/judge/comment/ai-summarize', postPayload)
      .then(response => {
        return response.data
      })
  }
}
