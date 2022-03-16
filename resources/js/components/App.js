import React from 'react';
import {Route, Switch, Redirect} from 'react-router-dom';

import LoginPage from "./pages/LoginPage";
import HomePage from "./pages/HomePage";
import PageNotFound from "./pages/PageNotFound";
import UsersPage from "./pages/UsersPage";
import DevicesPage from "./pages/DevicesPage";
import NotificationsPage from "./pages/NotificationsPage";
import CultivationTypesPage from "./pages/CultivationTypesPage";
import CultivationsPage from "./pages/CultivationsPage";

function App() {
  return (
      <Switch>
          <Route path='/' exact>
              <Redirect to="/home" />
          </Route>
          <Route path='/home'>
              <HomePage />
          </Route>
          <Route path='/users'>
              <UsersPage />
          </Route>
          <Route path='/cultivation-types'>
              <CultivationTypesPage />
          </Route>
          <Route path='/cultivations'>
              <CultivationsPage />
          </Route>
          <Route path='/devices'>
              <DevicesPage />
          </Route>
          <Route path='/notifications'>
              <NotificationsPage />
          </Route>
          <Route path='/auth'>
              <LoginPage />
          </Route>
          <Route path='*'>
              <PageNotFound />
          </Route>
      </Switch>
  );
}

export default App;
