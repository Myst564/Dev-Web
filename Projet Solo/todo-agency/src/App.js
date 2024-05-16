import React from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import Navbar from './components/Navbar';
import Home from './pages/Home';
import Login from './pages/Login';
import Register from './pages/Register';
import Tasks from './pages/Tasks';
import { AuthProvider } from './context/AuthContext';
import './App.css';

function App() {
    return (
      <AuthProvider>
        <Router>
          <div className="App">
            <Navbar />
            <Switch>
              <Route exact path="/" component={Home} />
              <Route path="/login" component={Login} />
              <Route path="/register" component={Register} />
              <Route path="/tasks" component={Tasks} />
            </Switch>
          </div>
        </Router>
      </AuthProvider>
    );
  }
  
  export default App;

