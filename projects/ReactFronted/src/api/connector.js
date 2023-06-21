import axios from 'axios';
import store from '../store/store';
import {setAPIToken} from "../slices/accountSlice";

const baseURL = 'http://card-game.local/';

const connector = axios.create({
    baseURL: baseURL,
    headers: {'Content-Type': 'application/json'},
    retry: 1,
    retryDelay: 500
},);

const checkToken = () => {
    return axios.post(baseURL + 'token/check', {
        "token": store.getState().account.value.APIToken
    });
}

const renewToken = () => {
    return axios.post(baseURL + 'token/renew', {
        "token": store.getState().account.value.APIToken
    })
};

connector.interceptors.request.use(
    async function (config) {
        if (store.getState().account.value.logged) {
            config.headers['Authorization'] = 'Bearer ' + store.getState().account.value.APIToken;
        }
        return config;
    }, (error) => {
        return Promise.reject(error);
    }
);

connector.interceptors.response.use(undefined, async (error) => {
        const {config, message} = error;
        if (!config || !config.retry) {
            return Promise.reject(error);
        }
        if (error.response.status !== 401) {
            return Promise.reject(error);
        }
        console.log('Unauthorized');
        await checkToken().then((response) => {
            if (!response.data.is_valid_token) {
                console.log('Expired or Non Valid Token');
                renewToken().then((response) => {
                    console.log('Renewed Token');
                    store.dispatch(setAPIToken(response.data.token));
                    localStorage.setItem('account', JSON.stringify({
                        "account_id": store.getState().account.value.account_id,
                        "API_token": response.data.token
                    }));
                    config.headers['Authorization'] = 'Bearer ' + store.getState().account.value.APIToken;
                }).catch((error) => {
                    return Promise.reject(error);
                })
            }
        });
        config.retry -= 1;
        const delayRetryRequest = new Promise((resolve) => {
            setTimeout(() => {
                console.log("Retry the original request");
                resolve();
            }, config.retryDelay || 1000);
        });
        return delayRetryRequest.then(() => axios(config));
    }
);
export default connector;
