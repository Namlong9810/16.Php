document.addEventListener("DOMContentLoaded", function() {
    const questionContainer = document.getElementById('question-container');
    const saveProgressButton = document.getElementById('save-progress-button');
    const logoutLink = document.getElementById('logout-link');
    const userNameSpan = document.getElementById('user-name');
    const welcomeMessage = document.getElementById('welcome-message');

    let currentScore = 0;
    let answeredQuestions = 0;
    let currentAnswered = 1;
    let useHelp5050 = false;
    let useHelpWiseMan = false;
    let useHelpCompanion= false;

    function loadHTML(elementId, url) {
        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById(elementId).innerHTML = data;
            })
            .catch(error => console.error('Error loading HTML:', error));
    }

    function logout() {
        fetch('user/logout.php')
            .then(response => {
                if (response.ok) {
                    console.log('Đăng xuất thành công.');
                    window.location.href = 'login.html';
                } else {
                    console.error('Đăng xuất thất bại.');
                }
            })
            .catch(error => console.error('Fetch error:', error));
    }

    function loadUserInfo() {
        fetch('user/get_user_infor.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json(); // Parse JSON từ phản hồi
            })
            .then(data => {
                if (data.username) {
                    userNameSpan.textContent = data.username;
                    welcomeMessage.style.display = 'inline-block';
                } else {
                    userNameSpan.textContent = Error('Lỗi');
                    throw new Error('User information not found');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error.message); // In ra thông báo lỗi
                // Xử lý các bước cần thiết khi xảy ra lỗi, ví dụ như hiển thị thông báo cho người dùng
            });
    }

    function loadQuestion() {
        fetch(`game/get_question.php?answered_questions=${answeredQuestions}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    questionContainer.innerHTML = `<p>${data.error}</p>`;
                } else {
                    questionContainer.innerHTML = `
                        <p>Câu hỏi số ${currentAnswered}: ${data.question}</p>
                        <button class="option-btn" data-option="A">${data.option_a}</button>
                        <button class="option-btn" data-option="B">${data.option_b}</button><br>
                        <button class="option-btn" data-option="C">${data.option_c}</button>
                        <button class="option-btn" data-option="D">${data.option_d}</button>
                    `;
                    document.querySelectorAll('.option-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const selectedOption = this.getAttribute('data-option');
                            checkAnswer(selectedOption, data.correct_option);
                        });
                    });
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                questionContainer.innerHTML = '<p>Có lỗi xảy ra khi tải câu hỏi.</p>';
            });
    }

    function checkAnswer(selectedOption, correctOption) {
        if (selectedOption === correctOption) {
            alert('Chính xác!');
            currentScore += 100;
            currentAnswered++;
            answeredQuestions++;
        } else {
            alert('Sai rồi!');
            currentScore = 0;
            currentAnswered = 1;
            answeredQuestions = 0;
        }
        saveGameSession();
        loadQuestion();
    }

    function saveGameSession() {
        fetch('game/save_session.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                score: currentScore,
                answered_Questions: answeredQuestions,
                use_help_50_50: useHelp5050,
                use_help_wise_man: useHelpWiseMan,
                use_help_companion: useHelpCompanion
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Save session error:', data.error);
            } else {
                console.log('Game session saved successfully.');
            }
        })
        .catch(error => console.error('Fetch error:', error));
    }

    loadHTML('header-placeholder', 'header/header.html');
    loadHTML('footer-placeholder', 'footer/footer.html');
    loadUserInfo();
    loadQuestion();

    saveProgressButton.addEventListener('click', saveGameSession);
    logoutLink.addEventListener('click', logout)
});
