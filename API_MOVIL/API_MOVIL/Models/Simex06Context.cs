using System;
using System.Collections.Generic;
using Microsoft.EntityFrameworkCore;

namespace API_MOVIL.Models;

public partial class Simex06Context : DbContext
{
    public Simex06Context()
    {
    }

    public Simex06Context(DbContextOptions<Simex06Context> options)
        : base(options)
    {
    }

    public virtual DbSet<Airport> Airports { get; set; }

    public virtual DbSet<Carrier> Carriers { get; set; }

    public virtual DbSet<City> Cities { get; set; }

    public virtual DbSet<Client> Clients { get; set; }

    public virtual DbSet<ClientRequest> ClientRequests { get; set; }

    public virtual DbSet<CommercialOffer> CommercialOffers { get; set; }

    public virtual DbSet<ContainerType> ContainerTypes { get; set; }

    public virtual DbSet<Country> Countries { get; set; }

    public virtual DbSet<Document> Documents { get; set; }

    public virtual DbSet<Incoterm> Incoterms { get; set; }

    public virtual DbSet<IncotermType> IncotermTypes { get; set; }

    public virtual DbSet<Location> Locations { get; set; }

    public virtual DbSet<LoginSession> LoginSessions { get; set; }

    public virtual DbSet<LogisticsOperation> LogisticsOperations { get; set; }

    public virtual DbSet<Port> Ports { get; set; }

    public virtual DbSet<Role> Roles { get; set; }

    public virtual DbSet<ShippingLine> ShippingLines { get; set; }

    public virtual DbSet<TrackingStep> TrackingSteps { get; set; }

    public virtual DbSet<User> Users { get; set; }

    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
#warning To protect potentially sensitive information in your connection string, you should move it out of source code. You can avoid scaffolding the connection string by using the Name= syntax to read it from configuration - see https://go.microsoft.com/fwlink/?linkid=2131148. For more guidance on storing connection strings, see https://go.microsoft.com/fwlink/?LinkId=723263.
        => optionsBuilder.UseSqlServer("Server=51.83.192.177;Database=simex06;User Id=simex06;Password=diversion2.0;Trusted_Connection=False;TrustServerCertificate=True;");

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.Entity<Airport>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__airports__3213E83FD2482016");

            entity.ToTable("airports");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.CityId).HasColumnName("city_id");
            entity.Property(e => e.Code)
                .HasMaxLength(5)
                .IsUnicode(false)
                .IsFixedLength()
                .HasColumnName("code");
            entity.Property(e => e.Name)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("name");

            entity.HasOne(d => d.City).WithMany(p => p.Airports)
                .HasForeignKey(d => d.CityId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_airports_cities");
        });

        modelBuilder.Entity<Carrier>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__carriers__3213E83FA7912423");

            entity.ToTable("carriers");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.CityId).HasColumnName("city_id");
            entity.Property(e => e.Name)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("name");

            entity.HasOne(d => d.City).WithMany(p => p.Carriers)
                .HasForeignKey(d => d.CityId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_carriers_cities");
        });

        modelBuilder.Entity<City>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__cities__3213E83F77FD2DCC");

            entity.ToTable("cities");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.CountryId).HasColumnName("country_id");
            entity.Property(e => e.Name)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("name");

            entity.HasOne(d => d.Country).WithMany(p => p.Cities)
                .HasForeignKey(d => d.CountryId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_cities_countries");
        });

        modelBuilder.Entity<Client>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__clients__3213E83F2993900C");

            entity.ToTable("clients");

            entity.HasIndex(e => e.VatNumber, "UQ_clients_vat_number").IsUnique();

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Address)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("address");
            entity.Property(e => e.CompanyName)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("company_name");
            entity.Property(e => e.ContactName)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("contact_name");
            entity.Property(e => e.Country)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("country");
            entity.Property(e => e.CreatedAt)
                .HasDefaultValueSql("(getdate())")
                .HasColumnType("datetime")
                .HasColumnName("created_at");
            entity.Property(e => e.CreatedBy).HasColumnName("created_by");
            entity.Property(e => e.DeletedAt)
                .HasColumnType("datetime")
                .HasColumnName("deleted_at");
            entity.Property(e => e.DeletedBy).HasColumnName("deleted_by");
            entity.Property(e => e.Email)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("email");
            entity.Property(e => e.OdooInt).HasColumnName("odoo_int");
            entity.Property(e => e.Phone)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("phone");
            entity.Property(e => e.PostalCode)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("postal_code");
            entity.Property(e => e.UpdatedAt)
                .HasColumnType("datetime")
                .HasColumnName("updated_at");
            entity.Property(e => e.UpdatedBy).HasColumnName("updated_by");
            entity.Property(e => e.VatNumber)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("vat_number");

            entity.HasOne(d => d.CreatedByNavigation).WithMany(p => p.ClientCreatedByNavigations)
                .HasForeignKey(d => d.CreatedBy)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_clients_created_by");

            entity.HasOne(d => d.DeletedByNavigation).WithMany(p => p.ClientDeletedByNavigations)
                .HasForeignKey(d => d.DeletedBy)
                .HasConstraintName("FK_clients_deleted_by");

            entity.HasOne(d => d.UpdatedByNavigation).WithMany(p => p.ClientUpdatedByNavigations)
                .HasForeignKey(d => d.UpdatedBy)
                .HasConstraintName("FK_clients_updated_by");
        });

        modelBuilder.Entity<ClientRequest>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__client_r__3213E83F56CE05F0");

            entity.ToTable("client_requests");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.ClientId).HasColumnName("client_id");
            entity.Property(e => e.Comments)
                .IsUnicode(false)
                .HasColumnName("comments");
            entity.Property(e => e.CreatedAt)
                .HasDefaultValueSql("(getdate())")
                .HasColumnType("datetime")
                .HasColumnName("created_at");
            entity.Property(e => e.CreatedBy).HasColumnName("created_by");
            entity.Property(e => e.DestinationId).HasColumnName("destination_id");
            entity.Property(e => e.GrossWeightKg)
                .HasColumnType("decimal(8, 2)")
                .HasColumnName("gross_weight_kg");
            entity.Property(e => e.OriginId).HasColumnName("origin_id");
            entity.Property(e => e.VolumeM3)
                .HasColumnType("decimal(8, 2)")
                .HasColumnName("volume_m3");

            entity.HasOne(d => d.Client).WithMany(p => p.ClientRequests)
                .HasForeignKey(d => d.ClientId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_client_requests_clients");

            entity.HasOne(d => d.CreatedByNavigation).WithMany(p => p.ClientRequests)
                .HasForeignKey(d => d.CreatedBy)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_client_requests_created_by");

            entity.HasOne(d => d.Destination).WithMany(p => p.ClientRequestDestinations)
                .HasForeignKey(d => d.DestinationId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_client_requests_destination_location");

            entity.HasOne(d => d.Origin).WithMany(p => p.ClientRequestOrigins)
                .HasForeignKey(d => d.OriginId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_client_requests_origin_location");
        });

        modelBuilder.Entity<CommercialOffer>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__commerci__3213E83F97258B6E");

            entity.ToTable("commercial_offers");

            entity.HasIndex(e => e.Reference, "UQ_commercial_offers_reference").IsUnique();

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.ClientId).HasColumnName("client_id");
            entity.Property(e => e.ClientRequestId).HasColumnName("client_request_id");
            entity.Property(e => e.Comments)
                .IsUnicode(false)
                .HasColumnName("comments");
            entity.Property(e => e.ContainerTypeId).HasColumnName("container_type_id");
            entity.Property(e => e.CreatedAt)
                .HasDefaultValueSql("(getdate())")
                .HasColumnType("datetime")
                .HasColumnName("created_at");
            entity.Property(e => e.CreatedBy).HasColumnName("created_by");
            entity.Property(e => e.DestinationPortId).HasColumnName("destination_port_id");
            entity.Property(e => e.IncotermId).HasColumnName("incoterm_id");
            entity.Property(e => e.OdooId).HasColumnName("odoo_id");
            entity.Property(e => e.OriginPortId).HasColumnName("origin_port_id");
            entity.Property(e => e.Price)
                .HasColumnType("decimal(12, 2)")
                .HasColumnName("price");
            entity.Property(e => e.Reference)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("reference");
            entity.Property(e => e.RejectionReason)
                .IsUnicode(false)
                .HasColumnName("rejection_reason");
            entity.Property(e => e.Status)
                .HasMaxLength(20)
                .IsUnicode(false)
                .HasDefaultValue("draft")
                .HasColumnName("status");
            entity.Property(e => e.UpdatedAt)
                .HasColumnType("datetime")
                .HasColumnName("updated_at");
            entity.Property(e => e.UpdatedBy).HasColumnName("updated_by");
            entity.Property(e => e.ValidUntil).HasColumnName("valid_until");

            entity.HasOne(d => d.Client).WithMany(p => p.CommercialOffers)
                .HasForeignKey(d => d.ClientId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_commercial_offers_clients");

            entity.HasOne(d => d.ClientRequest).WithMany(p => p.CommercialOffers)
                .HasForeignKey(d => d.ClientRequestId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_commercial_offers_request");

            entity.HasOne(d => d.ContainerType).WithMany(p => p.CommercialOffers)
                .HasForeignKey(d => d.ContainerTypeId)
                .HasConstraintName("FK_commercial_offers_containers");

            entity.HasOne(d => d.CreatedByNavigation).WithMany(p => p.CommercialOfferCreatedByNavigations)
                .HasForeignKey(d => d.CreatedBy)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_commercial_offers_created_by");

            entity.HasOne(d => d.DestinationPort).WithMany(p => p.CommercialOfferDestinationPorts)
                .HasForeignKey(d => d.DestinationPortId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_commercial_offers_dest_port");

            entity.HasOne(d => d.Incoterm).WithMany(p => p.CommercialOffers)
                .HasForeignKey(d => d.IncotermId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_commercial_offers_incoterms");

            entity.HasOne(d => d.OriginPort).WithMany(p => p.CommercialOfferOriginPorts)
                .HasForeignKey(d => d.OriginPortId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_commercial_offers_origin_port");

            entity.HasOne(d => d.UpdatedByNavigation).WithMany(p => p.CommercialOfferUpdatedByNavigations)
                .HasForeignKey(d => d.UpdatedBy)
                .HasConstraintName("FK_commercial_offers_updated_by");
        });

        modelBuilder.Entity<ContainerType>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__containe__3213E83FB80BA6A1");

            entity.ToTable("container_types");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.TypeName)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("type_name");
        });

        modelBuilder.Entity<Country>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__countrie__3213E83F51590F8D");

            entity.ToTable("countries");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Name)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("name");
        });

        modelBuilder.Entity<Document>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__document__3213E83F8F787577");

            entity.ToTable("documents");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.CreatedAt)
                .HasDefaultValueSql("(getdate())")
                .HasColumnType("datetime")
                .HasColumnName("created_at");
            entity.Property(e => e.DocumentTypeId).HasColumnName("document_type_id");
            entity.Property(e => e.EncryptionKey)
                .HasMaxLength(500)
                .IsUnicode(false)
                .HasColumnName("encryption_key");
            entity.Property(e => e.EntityId).HasColumnName("entity_id");
            entity.Property(e => e.EntityType)
                .HasMaxLength(30)
                .IsUnicode(false)
                .HasColumnName("entity_type");
            entity.Property(e => e.FileName)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("file_name");
            entity.Property(e => e.FilePath)
                .HasMaxLength(500)
                .IsUnicode(false)
                .HasColumnName("file_path");
            entity.Property(e => e.FileSizeBytes).HasColumnName("file_size_bytes");
            entity.Property(e => e.IsEncrypted).HasColumnName("is_encrypted");
            entity.Property(e => e.MimeType)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("mime_type");
            entity.Property(e => e.UploadedBy).HasColumnName("uploaded_by");

            entity.HasOne(d => d.UploadedByNavigation).WithMany(p => p.Documents)
                .HasForeignKey(d => d.UploadedBy)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_documents_users");
        });

        modelBuilder.Entity<Incoterm>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__incoterm__3213E83F20A03678");

            entity.ToTable("incoterms");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.IncotermTypeId).HasColumnName("incoterm_type_id");
            entity.Property(e => e.OrderNum).HasColumnName("order_num");
            entity.Property(e => e.TrackingStepId).HasColumnName("tracking_step_id");

            entity.HasOne(d => d.IncotermType).WithMany(p => p.Incoterms)
                .HasForeignKey(d => d.IncotermTypeId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_incoterms_incoterm_types");

            entity.HasOne(d => d.TrackingStep).WithMany(p => p.Incoterms)
                .HasForeignKey(d => d.TrackingStepId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_incoterms_tracking_steps");
        });

        modelBuilder.Entity<IncotermType>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__incoterm__3213E83FCF6F5E6E");

            entity.ToTable("incoterm_types");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Code)
                .HasMaxLength(5)
                .IsUnicode(false)
                .IsFixedLength()
                .HasColumnName("code");
            entity.Property(e => e.Name)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("name");
        });

        modelBuilder.Entity<Location>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__location__3213E83F8BE9CEA5");

            entity.ToTable("locations");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.CityId).HasColumnName("city_id");
            entity.Property(e => e.ClientId).HasColumnName("client_id");
            entity.Property(e => e.Latitude)
                .HasColumnType("decimal(10, 8)")
                .HasColumnName("latitude");
            entity.Property(e => e.Longitude)
                .HasColumnType("decimal(11, 8)")
                .HasColumnName("longitude");
            entity.Property(e => e.Name)
                .HasMaxLength(100)
                .IsUnicode(false)
                .HasColumnName("name");

            entity.HasOne(d => d.City).WithMany(p => p.Locations)
                .HasForeignKey(d => d.CityId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_locations_cities");

            entity.HasOne(d => d.Client).WithMany(p => p.Locations)
                .HasForeignKey(d => d.ClientId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_locations_clients");
        });

        modelBuilder.Entity<LoginSession>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__login_se__3213E83F637B68A9");

            entity.ToTable("login_sessions");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.DeviceType)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("device_type");
            entity.Property(e => e.IpAddress)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("ip_address");
            entity.Property(e => e.LoggedInAt)
                .HasColumnType("datetime")
                .HasColumnName("logged_in_at");
            entity.Property(e => e.LoggedOutAt)
                .HasColumnType("datetime")
                .HasColumnName("logged_out_at");
            entity.Property(e => e.TokenExpiresAt)
                .HasColumnType("datetime")
                .HasColumnName("token_expires_at");
            entity.Property(e => e.UserAgent)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("user_agent");
            entity.Property(e => e.UserId).HasColumnName("user_id");

            entity.HasOne(d => d.User).WithMany(p => p.LoginSessions)
                .HasForeignKey(d => d.UserId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_login_sessions_users");
        });

        modelBuilder.Entity<LogisticsOperation>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__logistic__3213E83F99B6078E");

            entity.ToTable("logistics_operations");

            entity.HasIndex(e => e.CommercialOfferId, "UQ_logistics_operations_offer").IsUnique();

            entity.HasIndex(e => e.Reference, "UQ_logistics_operations_reference").IsUnique();

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Ata).HasColumnName("ata");
            entity.Property(e => e.Atd).HasColumnName("atd");
            entity.Property(e => e.ClientId).HasColumnName("client_id");
            entity.Property(e => e.CommercialOfferId).HasColumnName("commercial_offer_id");
            entity.Property(e => e.CompletedAt)
                .HasColumnType("datetime")
                .HasColumnName("completed_at");
            entity.Property(e => e.CreatedAt)
                .HasDefaultValueSql("(getdate())")
                .HasColumnType("datetime")
                .HasColumnName("created_at");
            entity.Property(e => e.Eta).HasColumnName("eta");
            entity.Property(e => e.Etd).HasColumnName("etd");
            entity.Property(e => e.OdooId).HasColumnName("odoo_id");
            entity.Property(e => e.Reference)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("reference");
            entity.Property(e => e.Status)
                .HasMaxLength(30)
                .IsUnicode(false)
                .HasDefaultValue("preparation")
                .HasColumnName("status");
            entity.Property(e => e.UpdatedAt)
                .HasDefaultValueSql("(getdate())")
                .HasColumnType("datetime")
                .HasColumnName("updated_at");

            entity.HasOne(d => d.Client).WithMany(p => p.LogisticsOperations)
                .HasForeignKey(d => d.ClientId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_logistics_operations_clients");

            entity.HasOne(d => d.CommercialOffer).WithOne(p => p.LogisticsOperation)
                .HasForeignKey<LogisticsOperation>(d => d.CommercialOfferId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_logistics_operations_offers");
        });

        modelBuilder.Entity<Port>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__ports__3213E83F22214E09");

            entity.ToTable("ports");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.CityId).HasColumnName("city_id");
            entity.Property(e => e.Name)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("name");

            entity.HasOne(d => d.City).WithMany(p => p.Ports)
                .HasForeignKey(d => d.CityId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_ports_cities");
        });

        modelBuilder.Entity<Role>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__roles__3213E83FA05D34AE");

            entity.ToTable("roles");

            entity.HasIndex(e => e.Name, "UQ_roles_name").IsUnique();

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Description)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("description");
            entity.Property(e => e.Name)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("name");
        });

        modelBuilder.Entity<ShippingLine>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__shipping__3213E83FF42E0425");

            entity.ToTable("shipping_lines");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.CityId).HasColumnName("city_id");
            entity.Property(e => e.Name)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("name");

            entity.HasOne(d => d.City).WithMany(p => p.ShippingLines)
                .HasForeignKey(d => d.CityId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_shipping_lines_cities");
        });

        modelBuilder.Entity<TrackingStep>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__tracking__3213E83F7AAA640A");

            entity.ToTable("tracking_steps");

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.Name)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("name");
        });

        modelBuilder.Entity<User>(entity =>
        {
            entity.HasKey(e => e.Id).HasName("PK__users__3213E83FB222F1E9");

            entity.ToTable("users");

            entity.HasIndex(e => e.Email, "UQ_users_email").IsUnique();

            entity.Property(e => e.Id).HasColumnName("id");
            entity.Property(e => e.ClientId).HasColumnName("client_id");
            entity.Property(e => e.CreatedAt)
                .HasDefaultValueSql("(getdate())")
                .HasColumnType("datetime")
                .HasColumnName("created_at");
            entity.Property(e => e.CreatedBy).HasColumnName("created_by");
            entity.Property(e => e.DeletedAt)
                .HasColumnType("datetime")
                .HasColumnName("deleted_at");
            entity.Property(e => e.DeletedBy).HasColumnName("deleted_by");
            entity.Property(e => e.Email)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("email");
            entity.Property(e => e.FirstName)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("first_name");
            entity.Property(e => e.IsActive)
                .HasDefaultValue(true)
                .HasColumnName("is_active");
            entity.Property(e => e.LastName)
                .HasMaxLength(50)
                .IsUnicode(false)
                .HasColumnName("last_name");
            entity.Property(e => e.OdooId).HasColumnName("odoo_id");
            entity.Property(e => e.PasswordHash)
                .HasMaxLength(256)
                .IsUnicode(false)
                .HasColumnName("password_hash");
            entity.Property(e => e.PhoneNumber)
                .HasMaxLength(255)
                .IsUnicode(false)
                .HasColumnName("phone_number");
            entity.Property(e => e.RoleId).HasColumnName("role_id");
            entity.Property(e => e.UpdatedAt)
                .HasColumnType("datetime")
                .HasColumnName("updated_at");
            entity.Property(e => e.UpdatedBy).HasColumnName("updated_by");

            entity.HasOne(d => d.Client).WithMany(p => p.Users)
                .HasForeignKey(d => d.ClientId)
                .HasConstraintName("FK_users_clients");

            entity.HasOne(d => d.Role).WithMany(p => p.Users)
                .HasForeignKey(d => d.RoleId)
                .OnDelete(DeleteBehavior.ClientSetNull)
                .HasConstraintName("FK_users_roles");
        });

        OnModelCreatingPartial(modelBuilder);
    }

    partial void OnModelCreatingPartial(ModelBuilder modelBuilder);
}
