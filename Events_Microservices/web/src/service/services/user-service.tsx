import axios, {AxiosError} from "axios";
import {toast} from "react-toastify";
import {LoginUserRequest, LoginUserResponse, RegisterUserRequest, RegisterUserResponse} from "../model/user.tsx";


const API_URL = import.meta.env.VITE_API_URL;

const getAuthHeaders = () => ({
    Authorization: `Bearer ${localStorage.getItem("user_token")}`,
    "Content-Type": "application/json",
});

export const register = async (params: Omit<RegisterUserRequest, "id">): Promise<RegisterUserResponse> => {
    try {
        const response = await axios.post<RegisterUserResponse>(API_URL +'auth/register', params);
        toast.success(response.data.message);
        return response.data;
    }catch (error : any) {
        console.log(error)
        const errorData = error.response.data;
        if (errorData.errors) {
            const errorMessages = Object.values(errorData.errors).flat();
            errorMessages.forEach((msg : any) => toast.error(msg));
        } else {
            toast.error(errorData.error||  error.response.data.error ||  error.response.data.message);
        }
        throw error;

    }
};

export const login = async (param: Omit<LoginUserRequest, "id">): Promise<LoginUserResponse> => {
    try {
        const response = await axios.post<LoginUserResponse>(API_URL + `auth/login`,param)
        toast.success(response.data.message);
        return response.data;
    } catch (error : any) {
        const errorData = error.response.data;
        if (errorData.errors) {
            const errorMessages = Object.values(errorData.errors).flat();
            errorMessages.forEach((msg : any) => toast.error(msg));
        } else {
            toast.error(errorData.error ||  error.response.data.message ||  error.response.data.message);
        }
        throw error;
    }
};

export const fetchUser = async (id: number): Promise<any> => {
    try {
        const response = await axios.get<any>(`${API_URL}users/${id}`, { headers: getAuthHeaders() });
        toast.success(response.data.message);
        return response.data;
    } catch (error) {
        if (error instanceof AxiosError && error.response) {
            const errorMessage = error.response.data?.message || "Une erreur est survenue";
            toast.error(errorMessage);
        } else {
            toast.error("Erreur inconnue");
        }
        throw error;
    }
};

export const fetchUsers = async (): Promise<any> => {
    try {
        const response = await axios.get<any>(`${API_URL}users`, { headers: getAuthHeaders() });
        return response.data;
    } catch (error) {
        if (error instanceof AxiosError && error.response) {
            const errorMessage = error.response.data?.message || "Une erreur est survenue";
            toast.error(errorMessage);
        } else {
            toast.error("Erreur inconnue");
        }
        throw error;
    }
};

export const updateUser = async (id: number, params: any): Promise<any> => {
    try {
        const response = await axios.put<any>(`${API_URL}users/${id}`, params, { headers: getAuthHeaders() });
        toast.success(response.data.message);
        return response.data;

    }catch (error) {
        if (error instanceof AxiosError && error.response) {
            const errorMessage = error.response.data?.message || "Une erreur est survenue";
            toast.error(errorMessage);
        } else {
            toast.error("Erreur inconnue");
        }
        throw error;

    }
};

export const deleteUser = async (id: number): Promise<void> => {
    try {
        const response = await axios.delete(`${API_URL}users/${id}`, { headers: getAuthHeaders() });
        toast.success(response.data.message);
    } catch (error) {
        if (error instanceof AxiosError && error.response) {
            const errorMessage = error.response.data?.message || "Une erreur est survenue";
            toast.error(errorMessage);
        } else {
            toast.error("Erreur inconnue");
        }
        throw error;
    }
};