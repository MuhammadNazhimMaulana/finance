<template>
    <div>
        <div class="row layout-top-spacing" v-if="loading">
            <div class="col-12 text-center">
                <div class="loader multi-loader mx-auto"></div>
            </div>
        </div>
        <div class="row layout-top-spacing" v-else>
            <div class="col-xs-12 col-md-4 mb-3">
                <div class="card component-card_1 text-center">
                    <div class="card-body">
                        <h1><i class="fas fa-wallet"></i></h1>
                        <h5 class="card-title">Cash Balance</h5>
                        <h3 class="text-success">{{ cash }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4 mb-3">
                <div class="card component-card_1 text-center">
                    <div class="card-body">
                        <h1><i class="fas fa-hand-holding-heart"></i></h1>
                        <h5 class="card-title">Holding Balance</h5>
                        <h3 class="text-success">{{ holding }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-4 mb-3">
                <div class="card component-card_1 text-center">
                    <div class="card-body">
                        <h1><i class="fad fa-fire-smoke"></i></h1>
                        <h5 class="card-title">Total TAX</h5>
                        <h3 class="text-success">{{ tax }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '../../plugins/axios'
import currency from 'currency.js'

export default {
  name: 'RootBalanceComponent',
  data () {
    return {
      loading: true,
      cash: 0,
      holding: 0,
      tax: 0
    }
  },
  mounted() {
    let thisReplacer = this
    Promise.all([this.getCashBalance(), this.getHoldingBalance(), this.getTax()])
        .then(function (results) {
            const cashResult = results[0]
            const holdingResult = results[1]
            const taxResult = results[2]
            const IDR = value => currency(value, { symbol: "Rp", separator: ".", precision: 0 })

            if (cashResult.data.data.balance !== 'undefined') {
                thisReplacer.cash = IDR(cashResult.data.data.balance).format()
            }
            if (holdingResult.data.data.balance !== 'undefined') {
                thisReplacer.holding = IDR(holdingResult.data.data.balance).format()
            }
            if (taxResult.data.data.balance !== 'undefined') {
                thisReplacer.tax = IDR(taxResult.data.data.balance).format()
            }
            thisReplacer.loading = false
        })
            .catch(function (error) {
                // handle error
                console.log(error)
                thisReplacer.$swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi Kesalahan!',
                })
                thisReplacer.loading = false
            })
  },
  methods: {
    getCashBalance() {
        return axios.get('/payments/balance/CASH')
    },
    getHoldingBalance() {
        return axios.get('/payments/balance/HOLDING')
    },
    getTax() {
        return axios.get('/payments/balance/TAX')
    }
  }
}
</script>
