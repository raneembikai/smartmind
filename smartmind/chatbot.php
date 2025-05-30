<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartMind Flow</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-100 to-pink-200 min-h-screen font-sans">
    <div class="flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">

        <!-- Your flow interaction content here -->
        <div id="chat-container" class="bg-white shadow-2xl rounded-2xl p-10 w-full max-w-xl text-center">
            <h1 class="text-4xl font-bold text-green-700 mb-4">Welcome to SmartMind!</h1>
            <div id="chat-messages" class="mb-6"></div>
        </div>

    </div>

    <script>
        // Your JSON object from the provided data
        const flowData = {
            "platform": "WEBSITE",
            "payload": {
                "_id": "6814def55987761472635843",
                "default": true,
                "status": "ACTIVE",
                "description": "",
                "name": "My First Flow",
                "_bot": "6814def45987761472635840",
                "questions": [
                    {
                        "chatAssignment": {
                            "logic": "roundRobin",
                            "assignBy": "departments",
                            "teamMembers": [],
                            "departments": [],
                            "doNotassignToOfflineUsers": true,
                            "assignInBusinessHours": false
                        },
                        "aiConfig": {
                            "disclaimer": {
                                "isEnabled": true
                            },
                            "isSuggestionsEnabled": false,
                            "defaultSuggestions": [],
                            "useHistoryForDefaultSuggestions": false,
                            "suggestionPrompt": "",
                            "aiResponseBufferEnabled": false,
                            "aiResponseBufferDuration": 1
                        },
                        "status": "ACTIVE",
                        "label": "Welcome to SmartMind .",
                        "isLabelVisible": true,
                        "icon": "",
                        "message": {
                            "configureDomains": {
                                "domainAction": "exclude",
                                "selectedDomainsList": [],
                                "domainErrorMessage": "This domain is not Acceptable"
                            }
                        },
                        "type": "statement",
                        "_id": "6814def55987761472635844",
                        "id": "9b71a705-d5c0-4627-9ccd-40b82ae22909"
                    },
                    {
                        "chatAssignment": {
                            "logic": "roundRobin",
                            "assignBy": "departments",
                            "teamMembers": [],
                            "departments": [],
                            "doNotassignToOfflineUsers": true,
                            "assignInBusinessHours": false
                        },
                        "aiConfig": {
                            "disclaimer": {
                                "isEnabled": true
                            },
                            "isSuggestionsEnabled": false,
                            "defaultSuggestions": [],
                            "useHistoryForDefaultSuggestions": false,
                            "suggestionPrompt": "",
                            "aiResponseBufferEnabled": false,
                            "aiResponseBufferDuration": 1
                        },
                        "status": "ACTIVE",
                        "label": "How can we assist you ?",
                        "isLabelVisible": true,
                        "icon": "",
                        "message": {
                            "configureDomains": {
                                "domainAction": "exclude",
                                "selectedDomainsList": [],
                                "domainErrorMessage": "This domain is not Acceptable"
                            }
                        },
                        "type": "AI",
                        "_id": "5f33e8a0dc169947560501ab",
                        "id": "5dc3506c-6401-4e0a-aa9a-4cae1216e37d"
                    }
                ]
            }
        };

        // A function to render chat messages based on the flow data
        function renderChatFlow() {
            const chatContainer = document.getElementById('chat-messages');
            const questions = flowData.payload.questions;

            // Loop through the questions and display their labels
            questions.forEach((question, index) => {
                const messageElement = document.createElement('div');
                messageElement.classList.add('bg-gray-100', 'p-4', 'rounded', 'mb-4');
                
                let messageText = '';
                if (question.type === 'statement') {
                    messageText = `<p class="text-xl font-semibold">${question.label}</p>`;
                } else if (question.type === 'AI') {
                    messageText = `<p class="text-lg text-blue-600">${question.label}</p>`;
                }

                messageElement.innerHTML = messageText;
                chatContainer.appendChild(messageElement);
            });
        }

        // Call the function to render the chat flow
        renderChatFlow();
    </script>
</body>
</html>
