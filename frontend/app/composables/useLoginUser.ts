export const useLoginUser = () => {
  //todo config
  const apiBase = "http://localhost:8000/api";

  const login = async (email: string, password: number) => {
    try {
      const response = await $fetch(apiBase + "/login", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email: email, password: password }),
      });

      return response;
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || "Ошибка авторизации",
      };
    }
  };

  const me = async () => {
    const token = localStorage.getItem("token");
    if (!token){
        return {
            success: false,
            error: "Ошибка авторизации"
        }
    }

    try {
      const response = await $fetch(apiBase + "/me", {
        headers: { Authorization: `Bearer ${token}` },
      });

      return response;
    } catch (error) {
      return {
        success: false,
        error: error.data?.message || "Ошибка авторизации",
      };
    }
  };

  const register = async (name: string, email: string, password: string) => {
    try {
      const response = await $fetch(apiBase + "/register", {
        method: "POST",
        body: JSON.stringify({
          name: name,
          email: email,
          password: password,
        }),
      });

      return response;
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || "Ошибка авторизации",
      };
    }
  };

  const logout = async () => {
    const token = localStorage.getItem("token");

    try {
      let response = await $fetch(apiBase + "/logout", {
        method: "POST",
        headers: { Authorization: `Bearer ${token}`},
      });

      return response;
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || "Ошибка авторизации",
      };
    }
  };

  return {
    login,
    register,
    logout,
    me
  };
};