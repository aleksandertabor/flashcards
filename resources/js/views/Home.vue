<template>
  <div>
    <v-row justify="space-between">
      <v-col cols="12" md="6" v-if="!isLoggedIn" justify="center" class="text-center">
        <v-btn class="ma-2" x-large tile outlined color="success" :to="{name: 'register'}">
          <v-icon left>mdi-account-plus</v-icon>Register
        </v-btn>
        <v-btn class="ma-2" x-large tile outlined color="success" :to="{name: 'login'}">
          <v-icon left>mdi-account-arrow-left</v-icon>Login
        </v-btn>
      </v-col>
      <v-col cols="12" md="6" class="text-center" v-else>
        Hello {{user.username}}
        <v-btn
          class="ma-2"
          tile
          x-large
          outlined
          color="success"
          :to="{ name: 'profile', params: { username: user.username } }"
        >
          <v-icon left>mdi-account</v-icon>Profile
        </v-btn>
      </v-col>
      <v-col cols="12" md="6">
        <ApolloQuery :query="require('../queries/home.gql').stats" fetchPolicy="network-only">
          <template v-slot="{ result: { error, data }, isLoading }">
            <!-- Loading -->
            <loading v-if="isLoading" class="loading apollo"></loading>

            <!-- Result -->
            <v-row v-else-if="data" class="result apollo" justify="space-around">
              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn icon v-on="on">
                    <v-badge :content="data.usersTotal || '0'">
                      <v-icon large color="blue darken-2">mdi-account-circle</v-icon>
                    </v-badge>
                  </v-btn>
                </template>
                <span>All users</span>
              </v-tooltip>

              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn icon v-on="on">
                    <v-badge :content="data.decksTotal || '0'">
                      <v-icon large color="blue darken-2">mdi-image-album</v-icon>
                    </v-badge>
                  </v-btn>
                </template>
                <span>All decks</span>
              </v-tooltip>

              <v-tooltip top>
                <template v-slot:activator="{ on }">
                  <v-btn icon v-on="on">
                    <v-badge :content="data.cardsTotal || '0'">
                      <v-icon large color="blue darken-2">mdi-cards</v-icon>
                    </v-badge>
                  </v-btn>
                </template>
                <span>All cards</span>
              </v-tooltip>
            </v-row>

            <!-- No result -->
            <div v-else class="no-result apollo">No result :(</div>
          </template>
        </ApolloQuery>
      </v-col>
    </v-row>
    <v-row justify="space-between">
      <v-col cols="12" md="6">
        <ApolloQuery
          :query="require('../queries/home.gql').newestFiveDecks"
          fetchPolicy="network-only"
        >
          <template v-slot="{ result: { error, data }, isLoading }">
            <!-- Loading -->
            <loading v-if="isLoading" class="loading apollo"></loading>

            <!-- Result -->
            <div v-else-if="data.decks.data.length" class="result apollo">
              <v-card class="mx-auto">
                <v-toolbar color="pink" dark>
                  <v-toolbar-title>Last published decks</v-toolbar-title>
                </v-toolbar>

                <v-list two-line>
                  <v-list-item-group>
                    <template v-for="(deck, index) in data.decks.data">
                      <v-list-item
                        :key="'deck' + index"
                        :to="{name: 'deck',  params: {slug: deck.slug}}"
                      >
                        <v-list-item-content>
                          <v-list-item-title v-text="deck.username"></v-list-item-title>
                          <v-list-item-subtitle class="text--primary" v-text="deck.title"></v-list-item-subtitle>
                          <v-list-item-subtitle v-text="deck.description"></v-list-item-subtitle>
                        </v-list-item-content>

                        <v-list-item-action>
                          <v-list-item-action-text v-text="deck.created_at"></v-list-item-action-text>
                          <v-icon color="grey lighten-1">mdi-open-in-app</v-icon>
                        </v-list-item-action>
                      </v-list-item>

                      <v-divider v-if="index + 1 < data.decks.data.length" :key="index"></v-divider>
                    </template>
                  </v-list-item-group>
                </v-list>
              </v-card>
            </div>
          </template>
        </ApolloQuery>
      </v-col>
      <v-col cols="12" md="6">
        <ApolloQuery
          :query="require('../queries/home.gql').newestFiveUsers"
          fetchPolicy="network-only"
        >
          <template v-slot="{ result: { error, data }, isLoading }">
            <!-- Loading -->
            <loading v-if="isLoading" class="loading apollo"></loading>

            <!-- Result -->
            <div v-else-if="data.users.data.length" class="result apollo">
              <v-card class="mx-auto">
                <v-toolbar color="pink" dark>
                  <v-toolbar-title>Last registered users</v-toolbar-title>
                </v-toolbar>

                <v-list two-line>
                  <v-list-item-group>
                    <template v-for="(user, index) in data.users.data">
                      <v-list-item
                        :key="'user' + index"
                        :to="{name: 'profile',  params: {username: user.username}}"
                      >
                        <v-list-item-content>
                          <v-list-item-title v-text="user.username"></v-list-item-title>
                        </v-list-item-content>

                        <v-list-item-action>
                          <v-list-item-action-text v-text="user.created_at"></v-list-item-action-text>
                          <v-icon color="grey lighten-1">mdi-open-in-app</v-icon>
                        </v-list-item-action>
                      </v-list-item>

                      <v-divider v-if="index + 1 < data.users.data.length" :key="index"></v-divider>
                    </template>
                  </v-list-item-group>
                </v-list>
              </v-card>
            </div>
          </template>
        </ApolloQuery>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {};
</script>
