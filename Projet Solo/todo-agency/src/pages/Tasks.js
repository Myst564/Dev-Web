import React, { useState, useEffect, useContext } from 'react';
import axios from 'axios';
import AuthContext from '../context/AuthContext';

const Tasks = () => {
  const [tasks, setTasks] = useState([]);
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [status, setStatus] = useState('To Do');
  const { user } = useContext(AuthContext);

  useEffect(() => {
    const fetchTasks = async () => {
      const token = localStorage.getItem('auth-token');
      const res = await axios.get('/api/tasks', {
        headers: { 'x-auth-token': token }
      });
      setTasks(res.data);
    };
    fetchTasks();
  }, []);

  const addTask = async (e) => {
    e.preventDefault();
    const token = localStorage.getItem('auth-token');
    const newTask = { title, description, status, assignedTo: user.id };
    const res = await axios.post('/api/tasks', newTask, {
      headers: { 'x-auth-token': token }
    });
    setTasks([...tasks, res.data]);
    setTitle('');
    setDescription('');
  };

  return (
    <div>
      <h2>Tasks</h2>
      <form onSubmit={addTask}>
        <div>
          <label>Title</label>
          <input type="text" value={title} onChange={(e) => setTitle(e.target.value)} />
        </div>
        <div>
          <label>Description</label>
          <input type="text" value={description} onChange={(e) => setDescription(e.target.value)} />
        </div>
        <div>
          <label>Status</label>
          <select value={status} onChange={(e) => setStatus(e.target.value)}>
            <option value="To Do">To Do</option>
            <option value="In Progress">In Progress</option>
            <option value="Done">Done</option>
          </select>
        </div>
        <button type="submit">Add Task</button>
      </form>
      <ul>
        {tasks.map(task => (
          <li key={task._id}>
            <h3>{task.title}</h3>
            <p>{task.description}</p>
            <p>Status: {task.status}</p>
            <p>Assigned to: {task.assignedTo ? task.assignedTo.username : 'Unassigned'}</p>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default Tasks;


