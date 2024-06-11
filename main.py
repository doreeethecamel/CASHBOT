import json
import requests

headers ={"Authorization" : "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiY2E5MzdlMmUtZTFhMC00OTkwLWFjZDQtMmQwYzE4M2Y0MDQzIiwidHlwZSI6ImFwaV90b2tlbiJ9.XcgpXsPHLw0iZCi2szBD9pXDXLablmsJJQCi7-tvhD0"}

url = "https://api.edenai.run/v2/text/chat"
payload = {
    "providers": "openai/gpt-3.5-turbo",
    "text" : "Hello I need your help",
    "chatbot_global_action" : "Act as an assistant",
    "previous_history": [],
    "temperature": 0.0,
    "max_tokens": 150,
     
    }

response = requests.post(url, json=payload, headers=headers)

result = json.loads(response.text)
print(result)