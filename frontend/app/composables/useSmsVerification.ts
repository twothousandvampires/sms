export const useSmsVerification = () => {
  //todo config
  const apiBase = 'http://localhost:8000/api'

  const sendCode = async (phone: string) => {
    try {
      const response = await $fetch(`${apiBase}/send-code`, {
        method: 'POST',
        body: { phone },
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })

      return response
    } catch (error: any) {

      return { 
        success: false, 
        error: error.data?.message || 'Ошибка отправки кода' 
      }
    }
  }

  const verifyCode = async (phone: string, code: string) => {
    try {
      const response = await $fetch(`${apiBase}/verify-code`, {
        method: 'POST',
        body: { phone, code },
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      })

      return response
    } catch (error: any) {
      return { 
        success: false, 
        error: error.data?.message || 'Ошибка проверки кода' 
      }
    }
  }

  return {
    sendCode,
    verifyCode
  }
}