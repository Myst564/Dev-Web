const express = require('express');
const Task = require('../models/Task');
const auth = require('../middleware/auth');
const { translateAliases } = require('../models/User');

const router = express.Router();

// Create a new task
router.post('/', auth, async (req, res) => {
  const { title, description, status, assignedTo } = req.body;
  try {
    const newTask = new Task({
      title,
      description,
      status,
      assignedTo
    });
    const task = await newTask.save();
    res.json(task);
  } catch (err) {
    res.status(500).send('Server error');
  }
});

// Get all tasks
router.get('/', auth, async (req, res) => {
    try {
        const tasks = await Task.find().populate('assignedTo', 'username email');
        res.json(tasks);
    } catch (err) {
        res.status(500).send('Server error');
    }
});

// Update a task 
router.put('/:id', auth, async (req, res) => {
    const { title, description, status, assignedTo } = rep.body;
    try {
        let task = await Task.findById(req.params.id);
        if (!task) {
            return res.status(404).json({ msg: 'Task not found' });
        }
        task.title = title;
        task.description = description;
        task.status = status;
        task.assignedTo = assignedTo;
        await task.save();
        res.json(task);
    } catch (err) {
        res.status(500).send('Server error');
    }
});

// Delete a task 
router.delete('/:id', auth, async (req, res) => {
    try {
        let task = await Task.findById(req.params.id);
        if (!task) {
            return res.status(404).json({ msg: 'Task not found' });
        }
        await task.remove();
        res.json({ msg: 'Task removed' });
    } catch (err) {
        res.status(500).send('Server error');
    }
});

module.exports = router;