const chatbox = document.querySelector("#chat-messages");
const chatInput = document.querySelector("#chat-input #message-input");
const sendChatBtn = document.querySelector("#chat-input #send-button");

let userMessage = null; // Variable to store user's message
const EDEN_API_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiY2E5MzdlMmUtZTFhMC00OTkwLWFjZDQtMmQwYzE4M2Y0MDQzIiwidHlwZSI6ImFwaV90b2tlbiJ9.XcgpXsPHLw0iZCi2szBD9pXDXLablmsJJQCi7-tvhD0"; // Paste your Eden AI API key here
const inputInitHeight = chatInput.scrollHeight;

const createChatLi = (message, className) => {
    // Create a chat <li> element with passed message and className
    const chatLi = document.createElement("li");
    chatLi.classList.add("chat", `${className}`);
    let chatContent = className === "outgoing" ? `<p></p>` : `<span class="material-symbols-outlined">smart_toy</span><p></p>`;
    chatLi.innerHTML = chatContent;
    chatLi.querySelector("p").textContent = message;
    return chatLi; // return chat <li> element
}

const generateResponse = async (chatElement) => {
    const API_URL = "https://api.edenai.run/v2/text/chat";
    const messageElement = chatElement.querySelector("p");

    try {
        const response = await axios.post(API_URL, {
            providers: ["openai/gpt-3.5-turbo"], // Specify the providers here
            text: userMessage,
            chatbot_global_action: "Act as an assistant",
            previous_history: [],
            temperature: 0.0,
            max_tokens: 400,
            fallback_providers: ["google"]
        }, {
            headers: {
                Authorization: `Bearer ${EDEN_API_KEY}`
            }
        });
        // Check if the response is incomplete
        while (response.data["openai/gpt-3.5-turbo"].status === "incomplete") {
            // Fetch additional tokens to complete the response
            const additionalResponse = await axios.post(API_URL, {
                providers: ["openai/gpt-3.5-turbo"],
                text: "",
                chatbot_global_action: "Retrieve completion",
                previous_history: [],
                temperature: 0.0,
                max_tokens: 100,
                fallback_providers: ["google"]
            }, {
                headers: {
                    Authorization: `Bearer ${EDEN_API_KEY}`
                }
            });
            
            // Append additional tokens to the response
            response.data["openai/gpt-3.5-turbo"].generated_text += additionalResponse.data["openai/gpt-3.5-turbo"].generated_text;

            // Check if the response is now complete
            if (additionalResponse.data["openai/gpt-3.5-turbo"].status !== "incomplete") {
                break;
            }
        }
        console.log(response.data);
        const generatedText = response.data["openai/gpt-3.5-turbo"].generated_text; // Extract the bot's response from the API response
        messageElement.textContent = generatedText;
    } catch (error) {
        console.error("Error:", error);
        messageElement.classList.add("error");
        messageElement.textContent = "Oops! Something went wrong. Please try again.";
    } finally {
        chatbox.scrollTo(0, chatbox.scrollHeight);
    }
}



const handleChat = () => {
    userMessage = chatInput.value.trim(); // Get user entered message and remove extra whitespace
    if (!userMessage) return;

    // Clear the input textarea and set its height to default
    chatInput.value = "";
    chatInput.style.height = `${inputInitHeight}px`;

    // Append the user's message to the chatbox
    chatbox.appendChild(createChatLi(userMessage, "outgoing"));
    chatbox.scrollTo(0, chatbox.scrollHeight);

    setTimeout(() => {
        // Display "Thinking..." message while waiting for the response
        const incomingChatLi = createChatLi("Thinking...", "incoming");
        chatbox.appendChild(incomingChatLi);
        chatbox.scrollTo(0, chatbox.scrollHeight);
        generateResponse(incomingChatLi);
    }, 600);
}

chatInput.addEventListener("input", () => {
    // Adjust the height of the input textarea based on its content
    chatInput.style.height = `${inputInitHeight}px`;
    chatInput.style.height = `${chatInput.scrollHeight}px`;
});

chatInput.addEventListener("keydown", (e) => {
    // If Enter key is pressed without Shift key and the window 
    // width is greater than 800px, handle the chat
    if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
        e.preventDefault();
        handleChat();
    }
});

sendChatBtn.addEventListener("click", handleChat);
closeBtn.addEventListener("click", () => document.body.classList.remove("show-chatbot"));
chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));
