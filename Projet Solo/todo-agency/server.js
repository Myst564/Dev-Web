const express = require('express');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();
const port = process.env.PORT || 5000;

// Middleware
app.use(cors());
app.use(bodyParser.json());

// MongoDB connection
mongoose.connect('mongodb://localhost:27017/todo-agency', {
    useNewUrlParser: true,
    useUnifiedTopology: true
}).then(() => console.log('MongoDB connected'))
  .catch(err => console.log(err));


// Routes 
const users = require('./routes/users');
const tasks = require('./routes/tasks');

app.use('/api/users', users);
app.use('/api/tasks', tasks);

app.listen(port, () => {
    console.log('Server running on port ${port}');
});

