# -*- coding: utf-8 -*-
{
    'name': "Ticketing",
    'summary': "Gestion de tickets y operaciones de transporte",
    'description': "Modulo para la gestion de clientes, operaciones logisticas y tickets de incidencias.",
    'author': "Edith Miranda",
    'category': 'Operations',
    'version': '1.0',
    'depends': ['base', 'hr', 'mail'],
    'data': [
        'security/security_groups.xml',
        'security/ir.model.access.csv',
        'views/hr_employee_views.xml',
        'views/views.xml'
    ],
    'application': True,
}

