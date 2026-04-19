# -*- coding: utf-8 -*-
# from odoo import http


# class Ticketing(http.Controller):
#     @http.route('/ticketing/ticketing', auth='public')
#     def index(self, **kw):
#         return "Hello, world"

#     @http.route('/ticketing/ticketing/objects', auth='public')
#     def list(self, **kw):
#         return http.request.render('ticketing.listing', {
#             'root': '/ticketing/ticketing',
#             'objects': http.request.env['ticketing.ticketing'].search([]),
#         })

#     @http.route('/ticketing/ticketing/objects/<model("ticketing.ticketing"):obj>', auth='public')
#     def object(self, obj, **kw):
#         return http.request.render('ticketing.object', {
#             'object': obj
#         })

