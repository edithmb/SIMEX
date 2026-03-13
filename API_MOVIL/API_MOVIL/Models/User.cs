using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class User
{
    public int Id { get; set; }

    public int RoleId { get; set; }

    public int? ClientId { get; set; }

    public string FirstName { get; set; } = null!;

    public string LastName { get; set; } = null!;

    public string Email { get; set; } = null!;

    public string PasswordHash { get; set; } = null!;

    public string PhoneNumber { get; set; } = null!;

    public bool IsActive { get; set; }

    public int? OdooId { get; set; }

    public DateTime CreatedAt { get; set; }

    public int? CreatedBy { get; set; }

    public DateTime? UpdatedAt { get; set; }

    public int? UpdatedBy { get; set; }

    public DateTime? DeletedAt { get; set; }

    public int? DeletedBy { get; set; }

    public virtual Client? Client { get; set; }

    public virtual ICollection<Client> ClientCreatedByNavigations { get; set; } = new List<Client>();

    public virtual ICollection<Client> ClientDeletedByNavigations { get; set; } = new List<Client>();

    public virtual ICollection<ClientRequest> ClientRequests { get; set; } = new List<ClientRequest>();

    public virtual ICollection<Client> ClientUpdatedByNavigations { get; set; } = new List<Client>();

    public virtual ICollection<CommercialOffer> CommercialOfferCreatedByNavigations { get; set; } = new List<CommercialOffer>();

    public virtual ICollection<CommercialOffer> CommercialOfferUpdatedByNavigations { get; set; } = new List<CommercialOffer>();

    public virtual ICollection<Document> Documents { get; set; } = new List<Document>();

    public virtual ICollection<LoginSession> LoginSessions { get; set; } = new List<LoginSession>();

    public virtual Role Role { get; set; } = null!;
}
