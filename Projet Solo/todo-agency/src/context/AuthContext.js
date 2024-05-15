import React, { createContext, useState, useEffect } from 'react';
import axios from 'axios';

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState(null);

  useEffect(() => {
    const checkLoggedIn = async () => {
      const token = localStorage.getItem('auth-token');
      if (token) {
        const userRes = await axios.get('/api/users/me', {
          headers: { 'x-auth-token': token }
        });
        setUser(userRes.data);
      }
    };
    checkLoggedIn();
  }, []);

  const login = async (email, password) => {
    const res = await axios.post('/api/users/login', { email, password });
    localStorage.setItem('auth-token', res.data.token);
    const userRes = await axios.get('/api/users/me', {
      headers: { 'x-auth-token': res.data.token }
    });
    setUser(userRes.data);
  };

  const register = async (username, email, password) => {
    await axios.post('/api/users/register', { username, email, password });
  };

  const logout = () => {
    localStorage.removeItem('auth-token');
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export default AuthContext;


  