const actions = {
    login(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/login", payload)
                .then(response => {
                    const user = response.data;
                    context.commit('login', user);
                    localStorage.setItem('user', JSON.stringify(user))
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })
        })


    },
    register(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .post("/api/register", payload)
                .then(response => {
                    const user = response.data;
                    context.commit('login', user);
                    localStorage.setItem('user', JSON.stringify(user))
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })
        })


    },
    checkUser(context) {
        const user = localStorage.getItem('user');
        if (user) {
            context.commit('login', JSON.parse(user));
        }
    },
    logout(context) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${context.state.user.token}`;

        if (context.getters.isAuthenticated) {
            return new Promise((resolve, reject) => {
                axios
                    .post("/api/logout")
                    .then(response => {
                        localStorage.removeItem('user');
                        context.commit('logout');
                        resolve(response)
                    })
                    .catch(error => {
                        localStorage.removeItem('user');
                        context.commit('logout');
                        reject(error)
                    })
            })
        }

    },
    profile(context, payload) {
        return new Promise((resolve, reject) => {
            axios
                .get(`/api/users/${payload}`, )
                .then(response => {
                    resolve(response)
                })
                .catch(error => {
                    reject(error)
                })
        })


    },
}

export default actions;
