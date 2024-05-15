import React, { useContext } from 'react';
import { Link } from 'react-router-dom';
import AuthContext from '../context/AuthContext';

const Navbar = () => {
    const { user, logout } = useContext(AuthContext);

    return (
        <nav>
          <h1>Todo Agency</h1>
          <ul>
            <li><Link to="/">Home</Link></li>
            {user ? (
              <>
                <li><Link to="/tasks">Tasks</Link></li>
                <li><button onClick={logout}>Logout</button></li>
              </>
            ) : (
              <>
                <li><Link to="/login">Login</Link></li>
                <li><Link to="/register">Register</Link></li>
              </>
            )}
          </ul>
        </nav>
      );
    };
    
    export default Navbar;

