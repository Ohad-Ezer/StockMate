 const joinedGroups = {};
const groupNames = {
    'group1': 'NASDAQ',
    'group2': 'NYSE',
    'group3': 'FTSE 100',
    'group4': 'DAX',
    'group5': 'Nikkei 225',
    'group6': 'Hang Seng',
};

// Function to open the create group modal
function openCreateGroupModal() {
    var createGroupModal = document.getElementById('new-group-form');
    if (createGroupModal.style.display === 'block') {
        // If form is already visible, close it
        createGroupModal.style.display = 'none';
    } else {
        // Otherwise, show the form
        createGroupModal.style.display = 'block';
    }
}


// Function to close the chat box
function closeChatBox() {
    document.getElementById('chatBoxModal').style.display = 'none';
}

function getGroupName(groupId) {
    const groupCard = document.getElementById(groupId);

    if (groupCard) {
        const groupNameElement = groupCard.querySelector('h2');

        if (groupNameElement) {
            const groupName = groupNameElement.textContent.trim();
            console.log('Group Name:', groupName);
            return groupName;
        }
    }

    return groupId;
}


// Function to reload the page three times with a delay between each reload
function reloadPage(count) {
    if (count > 0) {
        setTimeout(() => {
            location.reload();
            reloadPage(count - 1); // Call reloadPage function recursively with decremented count
        }, 2000); // Reload the page with a delay of 2 seconds
    }
}

// Call the reloadPage function with the initial count of 3



// Call the reloadPage function

function joinGroup() {

    const groupId = document.getElementById('groupId').value;
    const membersElement = document.getElementById(groupId + '-members');

    // Check if the user has already joined
    const hasJoined = membersElement.dataset.hasJoined === 'true';

    if (membersElement && !hasJoined) {
        // Set group ID and action in the form inputs
        document.getElementById('groupIdInput').value = groupId;
        // The actionInput is already set to 'join' in the HTML

        // Submit the form
        
        document.getElementById('membershipForm').submit();
         // Reload the page after form submission
        for (let i = 0; i < 5; i++) {
            window.location.reload();
        }
    }
}


// Helper function to get the group name from the group card
function getGroupNameFromCard(groupId) {
    const groupCard = document.getElementById(groupId);
    if (groupCard) {
        const groupName = groupCard.dataset.name;
        return groupName;
    }
    return null;
}

function leaveGroup(groupId) {

    const membersElement = document.getElementById(groupId + '-members');
    if (membersElement) {
        // Check if the user has already joined
        const hasJoined = membersElement.dataset.hasJoined === 'true';

        if (hasJoined) {
            // Decrease the members count by 1, but not below 0
            const currentMembers = parseInt(membersElement.innerText.split(' ')[1]);
            membersElement.innerText = 'Members_amount: ' + Math.max(0, currentMembers - 1);

            // Reset the flag to indicate the user has left
            membersElement.dataset.hasJoined = 'false';
 window.location.href = window.location.href;
            // Update the joinedGroups object
            joinedGroups[groupId] = false;
        } else {
            alert('User has not joined or has already left.');
        }
    }

}


function showMyGroups() {
    var modal = document.getElementById('myGroupsModal');
    var groupsContainer = document.getElementById('my-groups-container');

    // Clear existing content
    groupsContainer.innerHTML = '';

    // Check if the user's groups variable is defined
    if (typeof userGroups !== 'undefined') {
        // Iterate over the user's groups
        userGroups.forEach(function(group) {
            var groupText = document.createElement('div');
            groupText.className = 'group-name';
            groupText.textContent = group.name;
            groupsContainer.appendChild(groupText);
        });

        // Display the modal only if there are groups
        if (userGroups.length > 0) {
            modal.style.display = 'block';
        } else {
            // Display notification if no groups are found
            var notification = document.getElementById('myGroupsNotification');
            notification.textContent = 'You are not a member of any group.';
            notification.style.display = 'block';

            // Hide the notification after a few seconds (adjust as needed)
            setTimeout(function () {
                notification.style.display = 'none';
            }, 3000);
        }
    }
}





function closeModal() {
    var modal = document.getElementById('myGroupsModal');
    modal.style.display = 'none';
}

// Close the modal if the user clicks outside of it
window.onclick = function (event) {
    var modal = document.getElementById('myGroupsModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

function showNewGroupForm() {
    var newGroupForm = document.getElementById('new-group-form');
    if (newGroupForm.style.display === 'block') {
        // If form is already visible, close it
        newGroupForm.style.display = 'none';
    } else {
        // Otherwise, show the form
        newGroupForm.style.display = 'block';
    }
}

const createdGroups = {}; // Object to store names of newly created groups

// Function to send a message
function sendMessage() {
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value;

    if (message.trim() !== '') {
        // For now, just append the message to the chatMessages div
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.innerHTML += `<p>${message}</p>`;
    }
}




function toggleAdditionalInfo(groupId) {
        const additionalInfo = document.querySelector(`#${groupId} .additional-info`);
        if (additionalInfo.style.display === 'none') {
            additionalInfo.style.display = 'block';
        } else {
            additionalInfo.style.display = 'none';
        }
    }


function openChatBox(groupId) {
    var chatBoxModal = document.getElementById('chatBoxModal');
    var chatMessages = document.getElementById('chatMessages');

    // Clear previous messages (if any)
    chatMessages.innerHTML = '';

    // Display the chat box modal
    chatBoxModal.style.display = 'block';

    // Generate the WhatsApp group link based on the groupId
    var whatsappGroupLink = generateWhatsAppGroupLink(groupId);

    // Display the WhatsApp group link in the chatMessages div
    chatMessages.innerHTML += `<p><a href="${whatsappGroupLink}" target="_blank">Join WhatsApp Group</a></p>`;
}

// Function to generate a WhatsApp group link based on groupId
function generateWhatsAppGroupLink(groupId) {
    return `https://chat.whatsapp.com/JtfKfeEaWwv5yHNy4U7vV9`;
}


