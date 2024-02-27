document.addEventListener('DOMContentLoaded', function () {
    const showModalButton = document.querySelector('.showModal');
    const cancelButton = document.querySelector('.cancel');
    const modal = document.getElementById('modal');

    showModalButton.addEventListener('click', function () {
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
        }, 10);
    });

    cancelButton.addEventListener('click', function () {
        modal.classList.add('opacity-0');
        modal.classList.remove('opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    });
});
