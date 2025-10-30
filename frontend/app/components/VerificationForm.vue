<template>
  <div class="container">
    <header class="header">
      <h1>SMS Верификация</h1>
    </header>

    <div v-if="!codeVerified" class="card">
      <h2>Отправить код подтверждения</h2>
    
      <form  @submit.prevent="handleSendCode" class="form">
        <div class="form-group">
          <label for="phone">Номер телефона:</label>
          <input
            id="phone"
            v-model="phone"
            type="tel"
            placeholder="+7 (999) 123-45-67"
            pattern="^(\+7|8)[\s\-]?[\(]?\d{3}[\)]?[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2}$"
            required
          />
        </div>
        <button type="submit" :disabled="loading || countdown > 0" class="btn primary">
          <span v-if="loading">Отправка...</span>
          <span v-else=>Отправить код <span v-if="countdown > 0">({{ countdown }})</span></span>
        </button>
      </form>
    </div>

    <div v-if="codeSent && !codeVerified" class="card">
      <h2>Подтверждение кода</h2>
      <p class="instruction">Код отправлен на номер: <strong>{{ phone }}</strong></p>
      
      <form @submit.prevent="handleVerifyCode" class="form">
        <div class="form-group">
          <label for="code">4-значный код из SMS:</label>
          <input
            id="code"
            v-model="verificationCode"
            type="text"
            placeholder="123456"
            maxlength="4"
            pattern="[0-9]{4}"
            required
          />
        </div>
        
        <div class="button-group">
          <button type="submit" :disabled="loading" class="btn success">
            <span v-if="loading">Проверка...</span>
            <span v-else>Подтвердить код</span>
          </button>
        </div>
      </form>
    </div>

    <div v-if="message" :class="['alert', messageType]">
      {{ message }}
    </div>

  </div>
</template>

<script setup lang="ts">

const { sendCode, verifyCode } = useSmsVerification()

const phone = ref('')
const verificationCode = ref('')
const codeSent = ref(false)
const codeVerified = ref(false)
const loading = ref(false)
const message = ref('')
const messageType = ref<'success' | 'error'>('success')

const countdown = ref(0)

    const startCountdown = () => {
        countdown.value = 60 
        let countdownInterval = setInterval(() => {
            countdown.value--
            if (countdown.value <= 0) {
                clearInterval(countdownInterval)
            }
        }, 1000)
    }

    const handleSendCode = async () => {
        loading.value = true
        message.value = ''
        
        const result = await sendCode(phone.value)
        
        if (result.success) {
            codeSent.value = true
            message.value = 'Код успешно отправлен! Проверьте ваши SMS.'
            messageType.value = 'success'

            startCountdown()
        } else {
            message.value = result.error || 'Произошла ошибка при отправке кода'
            messageType.value = 'error'
        }
    
        loading.value = false
    }

    const handleVerifyCode = async () => {
        loading.value = true
        message.value = ''
    
        const result = await verifyCode(phone.value, verificationCode.value)
    
        if (result.success) {
            message.value = 'Номер телефона успешно подтвержден!'
            messageType.value = 'success'
            codeVerified.value = true

        } else {
            message.value = result.error || 'Неверный код подтверждения'
            messageType.value = 'error'
        }
    
        loading.value = false
    }

</script>

<style scoped>
  @import url('~/assets/css/sms.css');
</style>