import { create } from "zustand";
import {RegisterUserRequest, User} from "../model/user.tsx";
import {deleteUser, fetchUser, fetchUsers, register, updateUser} from "../services/user-service.tsx";

interface UserState {
    users: User[];
    user: User | null;
    loading: boolean;
    isLoading : boolean;
    fetchUsers: () => Promise<void>;
    fetchUser: (id : number,role : string | undefined) => Promise<User | null>;
    createUser : (params: RegisterUserRequest) => Promise<void>;
    updateUser : (id: number, params : any)=> Promise<void>;
    deleteUser: (id: number) => Promise<void>;
    resetStore : () => void;

}

const useUserStore = create<UserState>((set) => ({
    users: [],
    user: null ,
    loading : false,
    isLoading : false,



    fetchUsers: async () => {
        set({ isLoading: true });
        try {
            const response = await fetchUsers();
            set({ users: response.data, isLoading: false });
        } catch (error) {
            console.error(error);
            set({ isLoading: false });
        }
    },


    fetchUser: async (id,role): Promise<User | null> => {
        try {
            const response = await fetchUser(id);
            const user = { ...response, role };
            set({ user });
            return response;
        } catch (error) {
            console.error(error);
            return null;
        }
    },

    createUser: async (userData) => {
        const response = await register(userData);
        set((state) => ({
            users: [...state.users, response.data.user],
        }));
    },

    updateUser: async (id, params)  => {
        set({ loading: true });
        try {
            const response  = await updateUser(id, params);

            set((state ) => ({
                users: state.users.map((h) => (h.id === id ? params : h)),
                user: params,
            }));
            return response;
        } catch (error) {
            console.error(error);
            return null;
        } finally {
            set({ loading: false });
        }
    },

    deleteUser: async (id) => {
        set({ loading: true });
        try {
            await deleteUser(id);
            set((state) => ({
                users: state.users.filter((h) => h.id !== id),
                user: state.user?.id === id ? null : state.user,
            }));
        } catch (error) {
            console.error("Erreur lors de la suppression de l'hôtel:", error);
        } finally {
            set({ loading: false });
        }
    },

    resetStore: () => {
        set({ user: null, loading: false });
    },

}));

export default useUserStore;
