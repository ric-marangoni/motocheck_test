import client from './client';

const GITHUB_LIST_REPOSITORIES = params =>
  client.get(`/reports/get-repositories`, {
    params,
  });

const GITHUB_IMPORT_DATA = params =>
    client.get(`/api/v1/get-github-repos`, {});


export default {
    gitgub_list_repositories: GITHUB_LIST_REPOSITORIES,
    gitgub_import_data: GITHUB_IMPORT_DATA
};
