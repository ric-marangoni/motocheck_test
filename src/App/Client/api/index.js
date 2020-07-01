import { head } from 'ramda';
import Omnichat from './github';

const GROUPED_ENDPOINTS = {
  ...Omnichat
};

const index = (endpoints = []) => {
  const mapped_endpoints = endpoints.map(endpoint => {
    if (typeof endpoint === 'string') {
      const fn = GROUPED_ENDPOINTS[endpoint];
      return fn();
    }

    if (typeof endpoint === 'object' && endpoint.hasOwnProperty('name')) {
      const fn = GROUPED_ENDPOINTS[endpoint.name];

      return fn(endpoint.params);
    }
  });

  return () => {
    return axios.all(mapped_endpoints).then(response => {
      if (response.length > 1) {
        return response.map(res => res.data);
      }

      return head(response).data;
    });
  };
};

export default index;
