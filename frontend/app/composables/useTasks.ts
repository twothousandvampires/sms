export const useTasks = () => {
  //todo config
  const apiBase = "http://localhost:8000/api";

  const getFilters = async () => {
    try {
      const response = await $fetch(apiBase + "/get-task-filters", {
        method: "POST",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      });

      return response;
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || "Не удалось получить фильтры",
      };
    }
  };

  const getTasks = async (filters = {}) => {
    try {
      const url = new URL(apiBase + "/tasks");

      Object.entries(filters).forEach(([key, value]) => {
        if (value !== undefined && value !== null && value !== "") {
          url.searchParams.append(key, value);
        }
      });

      const response = await $fetch(url.toString(), {
        method: "GET",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
          "Content-Type": "application/json",
          Accept: "application/json",
        },
      });

      return response;
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || "Не удалось получить задания",
      };
    }
  };

  const storeTask = async (data: FormData) => {
    try {
      const response = await $fetch(apiBase + "/tasks", {
        method: "POST",
        body: data,
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });

      return response;
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || "Ошибка при создании задачи",
      };
    }
  };

  const updateTask = async (data: FormData, task_id: number) => {
    try {
      data.append("_method", "PUT");
      const response = await $fetch(apiBase + "/tasks/" + task_id, {
        method: "POST",
        body: data,
        headers: {
          Accept: "application/json",
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });

      return response;
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || "Ошибка при обновлении задачи",
      };
    }
  };

  const deleteTask = async (task_id: number) => {
    try {
      const response = await $fetch(apiBase + "/tasks/" + task_id, {
        method: "DELETE",
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });

      return response;
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || "Ошибка при удалении задачи",
      };
    }
  };

  return {
    storeTask,
    getTasks,
    getFilters,
    deleteTask,
    updateTask,
  };
};