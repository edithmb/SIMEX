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
        'security/ir.model.access.csv',
        'views/views.xml',
    ],
    'application': True,
}

