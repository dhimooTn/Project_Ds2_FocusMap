const apiKey = 'AIzaSyBRLGX-ZHkHuTHz5emF-YatHF3KYw_Ws5Y'; // Replace with your actual API key

async function testGeminiAPI() {
  const url = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${apiKey}`;

  const requestBody = {
    contents: [
      {
        role: 'user',
        parts: [
          { text: 'Explain how AI works' },
        ],
      },
    ],
  };

  try {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(requestBody),
    });

    const data = await res.json();

    if (data && data.candidates && data.candidates.length > 0) {
      console.log('✅ API call successful. Response:');
      console.log(data.candidates[0].content.parts[0].text);
    } else {
      console.log('⚠️ API call did not return expected content.');
    }
  } catch (err) {
    console.error('❌ API call failed:', err);
  }
}

// Call the function to test
testGeminiAPI();
