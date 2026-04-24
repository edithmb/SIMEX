/**
 * Cliente del chat. Llama al webhook de n8n a través del proxy nginx
 * (/chat-api/webhook/simex-chat). El proxy enruta a n8n:5678 por la
 * red Docker externa 'simex-net'.
 */

const CHAT_ENDPOINT = '/chat-api/webhook/simex-chat'

export async function sendChatMessage(message, sessionId) {
  const res = await fetch(CHAT_ENDPOINT, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ message, sessionId }),
  })

  if (!res.ok) {
    throw new Error(`Chat request failed: ${res.status}`)
  }

  const data = await res.json()
  return data.respuesta ?? '(respuesta vacía)'
}
