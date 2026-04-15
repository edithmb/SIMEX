using System;
using System.Collections.Generic;

namespace API_MOVIL.Models;

public partial class PersonalDocumentsType
{
    public int Id { get; set; }

    public string Name { get; set; }

    public virtual ICollection<PersonalDocument> PersonalDocuments { get; set; } = new List<PersonalDocument>();
}
