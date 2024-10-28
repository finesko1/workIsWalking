export const toggleDropdown = (index, users) => {
    let activeButton = document.querySelector('.mainButton.rounded-t-xl');
    if (activeButton) {
        activeButton.classList.remove('rounded-t-xl');
        activeButton.classList.add('rounded-xl');
    }

    let dropdownButtons = document.getElementsByClassName('mainButton');

    users.value.forEach((user, i) => {
        user.showOptions = i === index ? !user.showOptions : false;
    });

    activeButton = dropdownButtons[index];

    if (users.value[index].showOptions) {
        activeButton.classList.remove('rounded-xl');
        activeButton.classList.add('rounded-t-xl');
    } else {
        activeButton.classList.remove('rounded-t-xl');
        activeButton.classList.add('rounded-xl');
    }
};

export const handleClickOutside = (users, event) => {
    if (!event.target.closest('.relative.dropdown')) {
        users.forEach(user => user.showOptions = false);

        const activeButton = document.querySelector('.mainButton.rounded-t-xl');
        if (activeButton) {
            activeButton.classList.remove('rounded-t-xl');
            activeButton.classList.add('rounded-xl');
        }
    }
};

export const hideMainButton = () => {
    const activeButton = document.querySelector('.mainButton.rounded-t-xl');
    if (activeButton) {
        activeButton.classList.remove('rounded-t-xl');
        activeButton.classList.add('rounded-xl');
    }
};

export const addFollowing = async (userId, loadSearchFriends, hideMainButton) => {
    try {
        await axios.post('/friendship/friends/following/' + userId);
        await loadSearchFriends();
        hideMainButton();
    } catch (e) {
        console.log(e.message);
    }
};

export const addFollower = async (userId, loadSearchFriends, hideMainButton) => {
    try {
        await axios.delete('/friendship/friends/' + userId);
        await loadSearchFriends();
        hideMainButton();
    } catch(e) {
        console.log(e.message);
    }
}

export const cancelFollowing = async (userId, loadSearchFriends, hideMainButton) => {
    try {
        await axios.delete('/friendship/friends/following/' + userId);
        await loadSearchFriends();
        hideMainButton();
    } catch(e) {
        console.log(e.message);
    }
}

export const addFriend = async (userId, loadSearchFriends, hideMainButton) => {
    try {
        await axios.post('/friendship/friends/' + userId);
        await loadSearchFriends();
        hideMainButton();
    } catch(e) {
        console.log(e.message);
    }
}

export const blockUser = async (userId, loadSearchFriends, hideMainButton) => {
    try {
        await axios.post('/friendship/friends/block/' + userId);
        await loadSearchFriends();
        hideMainButton();
    } catch (e) {
        console.log(e.message);
    }
}

export const unblockUser = async (userId, loadSearchFriends, hideMainButton) => {
    try {
        await axios.delete('/friendship/friends/block/' + userId);
        await loadSearchFriends();
        hideMainButton();
    } catch (e) {
        console.log(e.message);
    }
}
