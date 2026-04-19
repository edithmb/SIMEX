from odoo import models, fields

class HrEmployee(models.Model):
    _inherit = 'hr.employee'

    es_agente_comercial = fields.Boolean(
        string='És Agent Comercial (Gestor de Tickets)',
        default=False,
        help="Indica si aquest empleat s'encarrega de gestionar les incidències de transport."
    )