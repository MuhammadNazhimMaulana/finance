import axios from 'axios'

const token = document.head.querySelector('meta[name="csrf-token"]')

const instance = axios.create({
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
    'X-CSRF-TOKEN': token.content
  }
})

export default instance
