using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class Conversation
{
    public int Id { get; set; }

    public string? Title { get; set; }

    public DateTime CreatedAt { get; set; }

    public virtual ICollection<ConversationParticipant> ConversationParticipants { get; set; } = new List<ConversationParticipant>();

    public virtual ICollection<Message> Messages { get; set; } = new List<Message>();
}
