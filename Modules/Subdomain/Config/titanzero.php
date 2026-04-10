<?php

return {
    'module': 'subdomain',
    'capabilities': [
        {
            'key': 'subdomain.help.explain_page',
            'label': 'Subdomain: Explain this page',
            'risk': 'low',
            'requires': [],
            'handler': 'titanzero.intent.explain_page',
            'voice_phrases': [
                'what is this page',
                'explain this',
                'help me'
            ]
        },
        {
            'key': 'subdomain.contact',
            'label': 'Subdomain: Contact',
            'risk': 'low',
            'requires': [],
            'handler': 'contact',
            'voice_phrases': [
                'contact'
            ]
        },
        {
            'key': 'subdomain.contact-us',
            'label': 'Subdomain: Contact Us',
            'risk': 'low',
            'requires': [],
            'handler': 'contact-us',
            'voice_phrases': [
                'contact us'
            ]
        },
        {
            'key': 'subdomain.feature',
            'label': 'Subdomain: Feature',
            'risk': 'low',
            'requires': [],
            'handler': 'feature',
            'voice_phrases': [
                'feature'
            ]
        },
        {
            'key': 'subdomain.front.check-domain',
            'label': 'Subdomain: Check Domain',
            'risk': 'low',
            'requires': [],
            'handler': 'front.check-domain',
            'voice_phrases': [
                'check domain'
            ]
        },
        {
            'key': 'subdomain.front.contact',
            'label': 'Subdomain: Contact',
            'risk': 'low',
            'requires': [],
            'handler': 'front.contact',
            'voice_phrases': [
                'contact'
            ]
        },
        {
            'key': 'subdomain.front.contact-us',
            'label': 'Subdomain: Contact Us',
            'risk': 'low',
            'requires': [],
            'handler': 'front.contact-us',
            'voice_phrases': [
                'contact us'
            ]
        },
        {
            'key': 'subdomain.front.feature',
            'label': 'Subdomain: Feature',
            'risk': 'low',
            'requires': [],
            'handler': 'front.feature',
            'voice_phrases': [
                'feature'
            ]
        },
        {
            'key': 'subdomain.front.forgot-company',
            'label': 'Subdomain: Forgot Company',
            'risk': 'low',
            'requires': [],
            'handler': 'front.forgot-company',
            'voice_phrases': [
                'forgot company'
            ]
        },
        {
            'key': 'subdomain.front.get-email-verification',
            'label': 'Subdomain: Get Email Verification',
            'risk': 'low',
            'requires': [],
            'handler': 'front.get-email-verification',
            'voice_phrases': [
                'get email verification'
            ]
        },
        {
            'key': 'subdomain.front.home',
            'label': 'Subdomain: Home',
            'risk': 'low',
            'requires': [],
            'handler': 'front.home',
            'voice_phrases': [
                'home'
            ]
        },
        {
            'key': 'subdomain.front.pricing',
            'label': 'Subdomain: Pricing',
            'risk': 'low',
            'requires': [],
            'handler': 'front.pricing',
            'voice_phrases': [
                'pricing'
            ]
        },
        {
            'key': 'subdomain.front.signup.index',
            'label': 'Subdomain: Index',
            'risk': 'low',
            'requires': [],
            'handler': 'front.signup.index',
            'voice_phrases': [
                'index'
            ]
        },
        {
            'key': 'subdomain.front.submit-forgot-password',
            'label': 'Subdomain: Submit Forgot Password',
            'risk': 'low',
            'requires': [],
            'handler': 'front.submit-forgot-password',
            'voice_phrases': [
                'submit forgot password'
            ]
        },
        {
            'key': 'subdomain.front.super-admin-login',
            'label': 'Subdomain: Super Admin Login',
            'risk': 'low',
            'requires': [],
            'handler': 'front.super-admin-login',
            'voice_phrases': [
                'super admin login'
            ]
        },
        {
            'key': 'subdomain.front.workspace',
            'label': 'Subdomain: Workspace',
            'risk': 'low',
            'requires': [],
            'handler': 'front.workspace',
            'voice_phrases': [
                'workspace'
            ]
        },
        {
            'key': 'subdomain.home',
            'label': 'Subdomain: Home',
            'risk': 'low',
            'requires': [],
            'handler': 'home',
            'voice_phrases': [
                'home'
            ]
        },
        {
            'key': 'subdomain.login',
            'label': 'Subdomain: Login',
            'risk': 'low',
            'requires': [],
            'handler': 'login',
            'voice_phrases': [
                'login'
            ]
        },
        {
            'key': 'subdomain.notify.domain',
            'label': 'Subdomain: Domain',
            'risk': 'low',
            'requires': [],
            'handler': 'notify.domain',
            'voice_phrases': [
                'domain'
            ]
        },
        {
            'key': 'subdomain.password.request',
            'label': 'Subdomain: Request',
            'risk': 'low',
            'requires': [],
            'handler': 'password.request',
            'voice_phrases': [
                'request'
            ]
        },
        {
            'key': 'subdomain.password.reset',
            'label': 'Subdomain: Reset',
            'risk': 'low',
            'requires': [],
            'handler': 'password.reset',
            'voice_phrases': [
                'reset'
            ]
        },
        {
            'key': 'subdomain.pricing',
            'label': 'Subdomain: Pricing',
            'risk': 'low',
            'requires': [],
            'handler': 'pricing',
            'voice_phrases': [
                'pricing'
            ]
        },
        {
            'key': 'subdomain.push-notify-iframe',
            'label': 'Subdomain: Push Notify Iframe',
            'risk': 'low',
            'requires': [],
            'handler': 'push-notify-iframe',
            'voice_phrases': [
                'push notify iframe'
            ]
        },
        {
            'key': 'subdomain.super-admin.banned-subdomains.destroy',
            'label': 'Subdomain: Destroy',
            'risk': 'low',
            'requires': [],
            'handler': 'super-admin.banned-subdomains.destroy',
            'voice_phrases': [
                'destroy'
            ]
        },
        {
            'key': 'subdomain.super-admin.get.banned-subdomains',
            'label': 'Subdomain: Banned Subdomains',
            'risk': 'low',
            'requires': [],
            'handler': 'super-admin.get.banned-subdomains',
            'voice_phrases': [
                'banned subdomains'
            ]
        },
        {
            'key': 'subdomain.super-admin.post.banned-subdomains',
            'label': 'Subdomain: Banned Subdomains',
            'risk': 'low',
            'requires': [],
            'handler': 'super-admin.post.banned-subdomains',
            'voice_phrases': [
                'banned subdomains'
            ]
        }
    ],
    'go_enabled': true,
    'zero_enabled': true
};
