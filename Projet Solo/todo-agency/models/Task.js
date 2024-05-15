const mongoose = require('mongoose');

const TakeSchema = new mongoose.Schema({
    title: {
        type: String,
        required: true
    },
    description: {
        type: String, 
        required: true
    },
    status: {
        type: String, 
        enum: ['To Do', 'In Progress', 'Done'],
        default: 'To Do'
    },
    assignedTo: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User'
    },
    createdDate: {
        type: Date,
        default: Date.now
    }
})

module.exports = mongoose.model('Task', TakeSchema);