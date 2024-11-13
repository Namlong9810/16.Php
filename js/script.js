document.addEventListener("DOMContentLoaded", function() {
    const questionContainer = document.getElementById('question-container');
    const logoutLink = document.getElementById('logout-link');
    const userNameSpan = document.getElementById('user-name');
    const welcomeMessage = document.getElementById('welcome-message');
    const help5050Button = document.getElementById('help-50-50');
    const helpWiseMan = document.getElementById('help-wise-man');
    const helpCompanion  = document.getElementById('help-companion');

    
    let currentScore = 0; //Điểm số để lưu vào high score;
    let answeredQuestions = 0;
    let currentQuestion = 1;
    let useHelp5050 = false;
    let useHelpWiseMan = false;
    let useHelpCompanion = false;

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
                    window.location.href = 'Index.html';
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
            });
    }

    function loadGameSession() {
        fetch('game/load_session.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Load session error:', data.error);
                    questionContainer.innerHTML = '<p>Bạn cần phải đăng nhập để chơi.</p>';
                } else {
                    currentScore = data.current_score;
                    answeredQuestions = data.answered_questions;
                    useHelp5050 = data.use_help_50_50;
                    useHelpWiseMan = data.use_help_wise_man;
                    useHelpCompanion = data.use_help_companion;
                    currentQuestion = answeredQuestions + 1;
                    loadQuestion();
                }
            })
            .catch(error => console.error('Fetch error:', error));
    }

    function loadQuestion() {
        if (currentQuestion > 15) {
            questionContainer.innerHTML = '<p>Bạn đã hoàn thành trò chơi, dành được danh hiệu ai là triệu phú!!</p>';
            return;
        }
        fetch(`game/get_question.php?answered_questions=${answeredQuestions}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    questionContainer.innerHTML = `<p>${data.error}</p>`;
                } else {
                    questionContainer.innerHTML = `
                        <p>Điểm số hiện tại: ${currentScore}</p>
                        <p>Câu hỏi số ${currentQuestion}: ${data.question}</p>
                        <button class="option-btn" data-option="${data.option_a}">${data.option_a}</button>
                        <button class="option-btn" data-option="${data.option_b}">${data.option_b}</button><br>
                        <button class="option-btn" data-option="${data.option_c}">${data.option_c}</button>
                        <button class="option-btn" data-option="${data.option_d}">${data.option_d}</button>
                    `;
                    
                    // Lắng nghe sự kiện click cho các nút đáp án
                    document.querySelectorAll('.option-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            const selectedOption = this.getAttribute('data-option');
                            checkAnswer(selectedOption, data.correct_option);
                        });
                    });
    
                    // Gán sự kiện cho nút trợ giúp 50:50
                    if (!useHelp5050) {
                        help5050Button.addEventListener('click', function() {
                            use5050(data.correct_option, [data.option_a, data.option_b, data.option_c, data.option_d]);
                        });
                    }
    
                    // Gán sự kiện cho nút trợ giúp Wise Man
                    if (!useHelpWiseMan) {
                        helpWiseMan.addEventListener('click', function() {
                            useWiseMan(data.correct_option,[data.option_a, data.option_b, data.option_c, data.option_d]);
                        });
                    }
    
                    // Gán sự kiện cho nút trợ giúp Companion
                    if (!useHelpCompanion) {
                        helpCompanion.addEventListener('click', function() {
                            useCompanion(data.correct_option,[data.option_a, data.option_b, data.option_c, data.option_d]);
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                questionContainer.innerHTML = '<p>Có lỗi xảy ra khi tải câu hỏi.</p>';
            });
    }
    

    function checkAnswer(selectedOption, correct_option) {
        if (selectedOption === correct_option) {
            alert('Chính xác!');
            currentScore += 100;
            currentQuestion++;
            answeredQuestions++;
        } else {
            alert('Sai rồi! Cảm ơn bạn đã đến với chương trình và chúc bạn thành công trong cuộc sống ^^');
            currentScore = 0;
            currentQuestion = 1;
            answeredQuestions = 0;
            // Reset chức năng 50-50
            useHelp5050 = false;
            help5050Button.style.visibility = 'visible';
            
            // Reset chức năng Wise Man
            useHelpWiseMan = false;
            helpWiseMan.style.visibility = 'visible';
            // Reset chức năng Companion
            useHelpCompanion = false;
            helpCompanion.style.visibility = 'visible';
        }
        saveGameSession();
        loadQuestion();
    }
    
    function use5050(correct_option, options) {
        if(useHelp5050){
            return;
        }
        let wrongOptions = options.filter(option => option !== correct_option); // Lọc ra các đáp án sai
        let optionsToRemove = [];
    
        // Lấy ngẫu nhiên 2 đáp án sai
        while (optionsToRemove.length <2) {
            let randomIndex = Math.floor(Math.random() * wrongOptions.length);
            let option = wrongOptions[randomIndex];
    
            if (!optionsToRemove.includes(option)) {
                optionsToRemove.push(option);
            }
        }
    
        // Ẩn các nút có data-option là đáp án sai được chọn
        document.querySelectorAll('.option-btn').forEach(button => {
            if (optionsToRemove.includes(button.getAttribute('data-option'))) {
                button.style.visibility = 'hidden';
            }
        });
    
        useHelp5050 = true;
        help5050Button.style.visibility = 'hidden';
        saveGameSession();
    }
    
    function useWiseMan(correct_option, options) {
        if(useHelpWiseMan){
            return;
        }
        // Tạo một hàm để xác định câu trả lời dựa trên xác suất
        function generateAnswer(correct_option, options) {
            if (Math.random() < 0.80) {  // 80% xác suất
                return correct_option;
            } else {
                // Chọn ngẫu nhiên một đáp án sai
                let wrong_options = options.filter(option => option !== correct_option);
                return wrong_options[Math.floor(Math.random() * wrong_options.length)];
            }
        }
    
        let wiseManAnswer = generateAnswer(correct_option, options);
    
        alert(`Nhà thông thái học cho rằng đáp án đúng là: ${wiseManAnswer}`);
    
        useHelpWiseMan = true;
        helpWiseMan.style.visibility = 'hidden';
        saveGameSession();
    }
    function useCompanion(correct_option, options) {
        if (useHelpCompanion) {
            return;
        }
        // Tạo một hàm để xác định câu trả lời dựa trên xác suất
        function generateAnswer(correct_option, options) {
            if (Math.random() < 0.5) { // 50% xác suất
                return correct_option;
            } else {
                // Chọn ngẫu nhiên một đáp án sai
                let wrong_options_companion = options.filter(option => option !== correct_option);
                return wrong_options_companion[Math.floor(Math.random() * wrong_options_companion.length)];
            }
        }
    
        let CompanionAnswer = generateAnswer(correct_option, options);
    
        alert(`Người đồng hành thì cho rằng đáp án đúng là: ${CompanionAnswer}`);
    
        useHelpCompanion = true; // Đánh dấu là đã sử dụng chức năng Companion
        helpCompanion.style.visibility = 'hidden'; // Ẩn nút Companion
        saveGameSession();
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
    function loadLeaderboard() {
        fetch('user/leaderboard.php')
            .then(response => response.json())
            .then(data => {
                const leaderboardTableBody = document.getElementById('leaderboard').querySelector('tbody');
                if (data.length === 0) {
                    leaderboardTableBody.innerHTML = '<tr><td colspan="2">Không có dữ liệu bảng xếp hạng.</td></tr>';
                } else {
                    let leaderboardHTML = '';
                    data.forEach(user => {
                        leaderboardHTML += `
                            <tr>
                                <td>${user.username}</td>
                                <td>${user.high_score}</td>
                            </tr>
                        `;
                    });
                    leaderboardTableBody.innerHTML = leaderboardHTML;
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                document.getElementById('leaderboard').querySelector('tbody').innerHTML = '<tr><td colspan="2">Có lỗi xảy ra khi tải bảng xếp hạng.</td></tr>';
            });
    }
    loadHTML('header-placeholder', 'header/header.html');
    loadHTML('footer-placeholder', 'footer/footer.html');
    loadUserInfo();
    loadGameSession();
    loadLeaderboard();

    logoutLink.addEventListener('click', logout)
});
