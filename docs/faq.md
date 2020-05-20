# FAQ

## MissingCredentialsException when serving project.

See: [Netflex API/Configuration](/docs/api.md?id=configuration)

This happens because your projects is missing its credentials. Without the right credentials, the Netflex SDK can't communicate with the Netflex API.

In your project, you should have a `.env` file in the root path. Here you must configure the keys `NETFLEX_PUBLIC_KEY` and `NETFLEX_PRIVATE_KEY`. We recommend that you copy the [.env.example](https://github.com/NetflexSites/sdk-template-standard/blob/dev/.env.example) file to `.env`, and configure it from there.

> [!DANGER]
> Never commit the `.env` file. It should be added to your `.gitignore`. It is considered a potential security risk to have the keys in your Git history. If you need to configure the `.env` file when deploying to your development or production environment, please refer to [the guide](#configuring-environment-variables).

## QueryException (Invalid query: *) when using Models

See: [Models/Retrieving entries and performing queries](/docs/models.md?id=retrieving-entries-and-performing-queries)

This can happen if the internal [ElasticSearch](https://www.elastic.co/) index has not yet been indexed. This can typically occur if you are working on a new site, and its search indexes has not yet been generated.

> [!NOTE]
> You might also get this error simply due to performing an actually invalid query.

This can be fixed manually by going into Settings in Netflexapp, and performing a "Analyze indexes" action.

![Netflexapp: Analyze Indexes](../assets/netflexapp_analyze_indexes.png)
