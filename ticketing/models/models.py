# -*- coding: utf-8 -*-
from odoo import models, fields, api
from odoo.exceptions import UserError

# MODELO CLIENTE, cliente_ticketing
class ClienteTicketing(models.Model):
    _name = 'cliente.ticketing'
    _description = 'Client de Ticketing'

    name = fields.Char(string='Codi de Client', required=True)
    nom = fields.Char(string='Nom', required=True)
    cognom = fields.Char(string='Cognom', required=True)
    telefon = fields.Char(string='Telèfon')
    email = fields.Char(string='Email')
    adreca = fields.Char(string='Adreça')

    # Un cliente puede tener MUCHAS operaciones (One2many)
    operacio_ids = fields.One2many('operacio.ticketing', 'client_id', string='Operacions de Transport')

    #no se permite borrar si tiene una operacion asociada
    def unlink(self):
        for record in self:
            if record.operacio_ids:
                raise UserError(
                    f"No pots eliminar el client '{record.nom} {record.cognom}' "
                    f"perquè té operacions associades. Elimina-les primer."
                )
        return super(ClienteTicketing, self).unlink()


# MODELO OPERACIÓN DE TRANSPORTE, operacio_ticketing
class OperacioTicketing(models.Model):
    _name = 'operacio.ticketing'
    _description = 'Operació de Transport'

    name = fields.Char(string='Codi de l\'Operació', required=True)

    # Muchas operaciones pertenecen a UN cliente (Many2one)
    client_id = fields.Many2one('cliente.ticketing', string='Client', required=True, ondelete='restrict')

    # Origen y destino del transporte
    origen = fields.Char(string='Origen', required=True)
    desti = fields.Char(string='Destí', required=True)
    data = fields.Date(string='Data', required=True)

    # Incoterm: condiciones comerciales internacionales (EXW, FOB, CIF, etc.)
    incoterm = fields.Selection([
        ('EXW', 'EXW - Ex Works'),
        ('FOB', 'FOB - Free On Board'),
        ('CIF', 'CIF - Cost, Insurance and Freight'),
        ('DDP', 'DDP - Delivered Duty Paid'),
        ('DAP', 'DAP - Delivered At Place'),
    ], string='Incoterm', required=True)

    # Estado de la operación
    estat = fields.Selection([
        ('activa', 'Activa'),
        ('completada', 'Completada'),
        ('cancel_lada', 'Cancel·lada'),
    ], string='Estat', default='activa')

    # Una operación puede tener MUCHOS tickets (One2many)
    ticket_ids = fields.One2many('ticket.ticketing', 'operacio_id', string='Tickets')


    # Protección al borrar: no se puede eliminar una operación si tiene tickets
    def unlink(self):
        for record in self:
            if record.ticket_ids:
                raise UserError(
                    f"No pots eliminar l'operació '{record.name}' "
                    f"perquè té tickets associats. Elimina'ls primer."
                )
        return super(OperacioTicketing, self).unlink()

# MODELO TICKET, ticket_ticketing
# _inherit añade el Chatter (historial de mensajes y actividades)
class TicketTicketing(models.Model):
    _name = 'ticket.ticketing'
    _description = 'Ticket d\'Incidència'
    _inherit = ['mail.thread', 'mail.activity.mixin']

    # tracking=True registra cambios en el historial del Chatter
    name = fields.Char(string='Referència', required=True, tracking=True)

    # Muchos tickets pertenecen a UNA operación (Many2one)
    operacio_id = fields.Many2one('operacio.ticketing', string='Operació de Transport', required=True, ondelete='restrict')

    #tipo de ticket
    tipus = fields.Selection([
        ('reclamacio', 'Reclamació'),
        ('consulta', 'Consulta'),
        ('suggeriment', 'Suggeriment'),
    ], string='Tipus', required=True, tracking=True)

    # Estado del ticket con tracking para ver cambios en el Chatter
    estat = fields.Selection([
        ('creada', '1. Creada'),
        ('espera', '2. Espera de Revisió'),
        ('tancada', '3. Tancada'),
        ('rebutjada', '4. Rebutjada'),
    ], string='Estat', default='creada', required=True, tracking=True)

    descripcio = fields.Text(string='Descripció')
    data_creacio = fields.Date(string='Data de Creació', default=fields.Date.today)

    # Empleado que abre el ticket
    # domain filtra para mostrar solo empleados marcados como agentes comerciales
    empleat_id = fields.Many2one('hr.employee', string='Empleat Assignat', required=True)




