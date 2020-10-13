/*!
 * jQuery QueryBuilder 2.5.2
 * Copyright 2014-2018 Damien "Mistic" Sorel (http://www.strangeplanet.fr)
 * Licensed under MIT (https://opensource.org/licenses/MIT)
 */

!function (e, t) {
    "function" == typeof define && define.amd ? define("jQuery.extendext", ["jquery"], t) : "object" == typeof module && module.exports ? module.exports = t(require("jquery")) : t(e.jQuery)
}(this, function ($) {
    "use strict";
    $.extendext = function () {
        var e, t, r, n, i, o, l = arguments[0] || {}, s = 1, a = arguments.length, u = !1, p = "default";
        for ("boolean" == typeof l && (u = l, l = arguments[s++] || {}), "string" == typeof l && ("concat" !== (p = l.toLowerCase()) && "replace" !== p && "extend" !== p && (p = "default"), l = arguments[s++] || {}), "object" == typeof l || $.isFunction(l) || (l = {}), s === a && (l = this, s--); s < a; s++) if (null !== (e = arguments[s])) if ($.isArray(e) && "default" !== p) switch (o = l && $.isArray(l) ? l : [], p) {
            case"concat":
                l = o.concat($.extend(u, [], e));
                break;
            case"replace":
                l = $.extend(u, [], e);
                break;
            case"extend":
                e.forEach(function (e, t) {
                    if ("object" == typeof e) {
                        var r = $.isArray(e) ? [] : {};
                        o[t] = $.extendext(u, p, o[t] || r, e)
                    } else -1 === o.indexOf(e) && o.push(e)
                }), l = o
        } else for (t in e) r = l[t], l !== (n = e[t]) && (u && n && ($.isPlainObject(n) || (i = $.isArray(n))) ? (i ? (i = !1, o = r && $.isArray(r) ? r : []) : o = r && $.isPlainObject(r) ? r : {}, l[t] = $.extendext(u, p, o, n)) : void 0 !== n && (l[t] = n));
        return l
    }
}), function () {
    "use strict";
    var a, u = {
        name: "doT",
        version: "1.1.1",
        templateSettings: {
            evaluate: /\{\{([\s\S]+?(\}?)+)\}\}/g,
            interpolate: /\{\{=([\s\S]+?)\}\}/g,
            encode: /\{\{!([\s\S]+?)\}\}/g,
            use: /\{\{#([\s\S]+?)\}\}/g,
            useParams: /(^|[^\w$])def(?:\.|\[[\'\"])([\w$\.]+)(?:[\'\"]\])?\s*\:\s*([\w$\.]+|\"[^\"]+\"|\'[^\']+\'|\{[^\}]+\})/g,
            define: /\{\{##\s*([\w\.$]+)\s*(\:|=)([\s\S]+?)#\}\}/g,
            defineParams: /^\s*([\w$]+):([\s\S]+)/,
            conditional: /\{\{\?(\?)?\s*([\s\S]*?)\s*\}\}/g,
            iterate: /\{\{~\s*(?:\}\}|([\s\S]+?)\s*\:\s*([\w$]+)\s*(?:\:\s*([\w$]+))?\s*\}\})/g,
            varname: "it",
            strip: !0,
            append: !0,
            selfcontained: !1,
            doNotSkipEncoded: !1
        },
        template: void 0,
        compile: void 0,
        log: !0
    };
    u.encodeHTMLSource = function (e) {
        var t = {"&": "&#38;", "<": "&#60;", ">": "&#62;", '"': "&#34;", "'": "&#39;", "/": "&#47;"},
            r = e ? /[&<>"'\/]/g : /&(?!#?\w+;)|<|>|"|'|\//g;
        return function (e) {
            return e ? e.toString().replace(r, function (e) {
                return t[e] || e
            }) : ""
        }
    }, a = function () {
        return this || (0, eval)("this")
    }(), "undefined" != typeof module && module.exports ? module.exports = u : "function" == typeof define && define.amd ? define("doT", function () {
        return u
    }) : a.doT = u;
    var p = {
        append: {start: "'+(", end: ")+'", startencode: "'+encodeHTML("},
        split: {start: "';out+=(", end: ");out+='", startencode: "';out+=encodeHTML("}
    }, d = /$^/;

    function c(e) {
        return e.replace(/\\('|\\)/g, "$1").replace(/[\r\t\n]/g, " ")
    }

    u.template = function (e, t, r) {
        var n, i, o = (t = t || u.templateSettings).append ? p.append : p.split, l = 0,
            s = t.use || t.define ? function n(i, e, o) {
                return ("string" == typeof e ? e : e.toString()).replace(i.define || d, function (e, n, t, r) {
                    return 0 === n.indexOf("def.") && (n = n.substring(4)), n in o || (":" === t ? (i.defineParams && r.replace(i.defineParams, function (e, t, r) {
                        o[n] = {arg: t, text: r}
                    }), n in o || (o[n] = r)) : new Function("def", "def['" + n + "']=" + r)(o)), ""
                }).replace(i.use || d, function (e, t) {
                    i.useParams && (t = t.replace(i.useParams, function (e, t, r, n) {
                        if (o[r] && o[r].arg && n) {
                            var i = (r + ":" + n).replace(/'|\\/g, "_");
                            return o.__exp = o.__exp || {}, o.__exp[i] = o[r].text.replace(new RegExp("(^|[^\\w$])" + o[r].arg + "([^\\w$])", "g"), "$1" + n + "$2"), t + "def.__exp['" + i + "']"
                        }
                    }));
                    var r = new Function("def", "return " + t)(o);
                    return r ? n(i, r, o) : r
                })
            }(t, e, r || {}) : e;
        s = ("var out='" + (t.strip ? s.replace(/(^|\r|\n)\t* +| +\t*(\r|\n|$)/g, " ").replace(/\r|\n|\t|\/\*[\s\S]*?\*\//g, "") : s).replace(/'|\\/g, "\\$&").replace(t.interpolate || d, function (e, t) {
            return o.start + c(t) + o.end
        }).replace(t.encode || d, function (e, t) {
            return n = !0, o.startencode + c(t) + o.end
        }).replace(t.conditional || d, function (e, t, r) {
            return t ? r ? "';}else if(" + c(r) + "){out+='" : "';}else{out+='" : r ? "';if(" + c(r) + "){out+='" : "';}out+='"
        }).replace(t.iterate || d, function (e, t, r, n) {
            return t ? (l += 1, i = n || "i" + l, t = c(t), "';var arr" + l + "=" + t + ";if(arr" + l + "){var " + r + "," + i + "=-1,l" + l + "=arr" + l + ".length-1;while(" + i + "<l" + l + "){" + r + "=arr" + l + "[" + i + "+=1];out+='") : "';} } out+='"
        }).replace(t.evaluate || d, function (e, t) {
            return "';" + c(t) + "out+='"
        }) + "';return out;").replace(/\n/g, "\\n").replace(/\t/g, "\\t").replace(/\r/g, "\\r").replace(/(\s|;|\}|^|\{)out\+='';/g, "$1").replace(/\+''/g, ""), n && (t.selfcontained || !a || a._encodeHTML || (a._encodeHTML = u.encodeHTMLSource(t.doNotSkipEncoded)), s = "var encodeHTML = typeof _encodeHTML !== 'undefined' ? _encodeHTML : (" + u.encodeHTMLSource.toString() + "(" + (t.doNotSkipEncoded || "") + "));" + s);
        try {
            return new Function(t.varname, s)
        } catch (e) {
            throw"undefined" != typeof console && console.log("Could not create a template function: " + s), e
        }
    }, u.compile = function (e, t) {
        return u.template(e, null, t)
    }
}(), function (e, t) {
    "function" == typeof define && define.amd ? define("query-builder", ["jquery", "dot/doT", "jquery-extendext"], t) : "object" == typeof module && module.exports ? module.exports = t(require("jquery"), require("dot/doT"), require("jquery-extendext")) : t(e.jQuery, e.doT)
}(this, function ($, r) {
    "use strict";
    var c = function (e, t) {
        (e[0].queryBuilder = this).$el = e, this.settings = $.extendext(!0, "replace", {}, c.DEFAULTS, t), this.model = new n, this.status = {
            id: null,
            generated_id: !1,
            group_id: 0,
            rule_id: 0,
            has_optgroup: !1,
            has_operator_optgroup: !1
        }, this.filters = this.settings.filters, this.icons = this.settings.icons, this.operators = this.settings.operators, this.templates = this.settings.templates, this.plugins = this.settings.plugins, this.lang = null, void 0 === c.regional.en && h.error("Config", '"i18n/en.js" not loaded.'), this.lang = $.extendext(!0, "replace", {}, c.regional.en, c.regional[this.settings.lang_code], this.settings.lang), !1 === this.settings.allow_groups ? this.settings.allow_groups = 0 : !0 === this.settings.allow_groups && (this.settings.allow_groups = -1), Object.keys(this.templates).forEach(function (e) {
            this.templates[e] || (this.templates[e] = c.templates[e]), "string" == typeof this.templates[e] && (this.templates[e] = r.template(this.templates[e]))
        }, this), this.$el.attr("id") || (this.$el.attr("id", "qb_" + Math.floor(99999 * Math.random())), this.status.generated_id = !0), this.status.id = this.$el.attr("id"), this.$el.addClass("query-builder form-inline"), this.filters = this.checkFilters(this.filters), this.operators = this.checkOperators(this.operators), this.bindEvents(), this.initPlugins()
    };
    $.extend(c.prototype, {
        trigger: function (e) {
            var t = new $.Event(this._tojQueryEvent(e), {builder: this});
            return this.$el.triggerHandler(t, Array.prototype.slice.call(arguments, 1)), t
        }, change: function (e, t) {
            var r = new $.Event(this._tojQueryEvent(e, !0), {builder: this, value: t});
            return this.$el.triggerHandler(r, Array.prototype.slice.call(arguments, 2)), r.value
        }, on: function (e, t) {
            return this.$el.on(this._tojQueryEvent(e), t), this
        }, off: function (e, t) {
            return this.$el.off(this._tojQueryEvent(e), t), this
        }, once: function (e, t) {
            return this.$el.one(this._tojQueryEvent(e), t), this
        }, _tojQueryEvent: function (e, t) {
            return e.split(" ").map(function (e) {
                return e + ".queryBuilder" + (t ? ".filter" : "")
            }).join(" ")
        }
    }), c.types = {
        string: "string",
        integer: "number",
        double: "number",
        date: "datetime",
        time: "datetime",
        datetime: "datetime",
        boolean: "boolean"
    }, c.inputs = ["text", "number", "textarea", "radio", "checkbox", "select"], c.modifiable_options = ["display_errors", "allow_groups", "allow_empty", "default_condition", "default_filter"], c.selectors = {
        group_container: ".rules-group-container",
        rule_container: ".rule-container",
        filter_container: ".rule-filter-container",
        operator_container: ".rule-operator-container",
        value_container: ".rule-value-container",
        error_container: ".error-container",
        condition_container: ".rules-group-header .group-conditions",
        rule_header: ".rule-header",
        group_header: ".rules-group-header",
        group_actions: ".group-actions",
        rule_actions: ".rule-actions",
        rules_list: ".rules-group-body>.rules-list",
        group_condition: ".rules-group-header [name$=_cond]",
        rule_filter: ".rule-filter-container [name$=_filter]",
        rule_operator: ".rule-operator-container [name$=_operator]",
        rule_value: ".rule-value-container [name*=_value_]",
        add_rule: "[data-add=rule]",
        delete_rule: "[data-delete=rule]",
        add_group: "[data-add=group]",
        delete_group: "[data-delete=group]"
    }, c.templates = {}, c.regional = {}, c.OPERATORS = {
        equal: {type: "equal", nb_inputs: 1, multiple: !1, apply_to: ["string", "number", "datetime", "boolean"]},
        not_equal: {
            type: "not_equal",
            nb_inputs: 1,
            multiple: !1,
            apply_to: ["string", "number", "datetime", "boolean"]
        },
        in: {type: "in", nb_inputs: 1, multiple: !0, apply_to: ["string", "number", "datetime"]},
        not_in: {type: "not_in", nb_inputs: 1, multiple: !0, apply_to: ["string", "number", "datetime"]},
        less: {type: "less", nb_inputs: 1, multiple: !1, apply_to: ["number", "datetime"]},
        less_or_equal: {type: "less_or_equal", nb_inputs: 1, multiple: !1, apply_to: ["number", "datetime"]},
        greater: {type: "greater", nb_inputs: 1, multiple: !1, apply_to: ["number", "datetime"]},
        greater_or_equal: {type: "greater_or_equal", nb_inputs: 1, multiple: !1, apply_to: ["number", "datetime"]},
        between: {type: "between", nb_inputs: 2, multiple: !1, apply_to: ["number", "datetime"]},
        not_between: {type: "not_between", nb_inputs: 2, multiple: !1, apply_to: ["number", "datetime"]},
        begins_with: {type: "begins_with", nb_inputs: 1, multiple: !1, apply_to: ["string"]},
        not_begins_with: {type: "not_begins_with", nb_inputs: 1, multiple: !1, apply_to: ["string"]},
        contains: {type: "contains", nb_inputs: 1, multiple: !1, apply_to: ["string"]},
        not_contains: {type: "not_contains", nb_inputs: 1, multiple: !1, apply_to: ["string"]},
        ends_with: {type: "ends_with", nb_inputs: 1, multiple: !1, apply_to: ["string"]},
        not_ends_with: {type: "not_ends_with", nb_inputs: 1, multiple: !1, apply_to: ["string"]},
        is_empty: {type: "is_empty", nb_inputs: 0, multiple: !1, apply_to: ["string"]},
        is_not_empty: {type: "is_not_empty", nb_inputs: 0, multiple: !1, apply_to: ["string"]},
        is_null: {type: "is_null", nb_inputs: 0, multiple: !1, apply_to: ["string", "number", "datetime", "boolean"]},
        is_not_null: {
            type: "is_not_null",
            nb_inputs: 0,
            multiple: !1,
            apply_to: ["string", "number", "datetime", "boolean"]
        }
    }, c.DEFAULTS = {
        filters: [],
        plugins: [],
        sort_filters: !1,
        display_errors: !0,
        allow_groups: -1,
        allow_empty: !1,
        conditions: ["AND", "OR"],
        default_condition: "AND",
        inputs_separator: " , ",
        select_placeholder: "------",
        display_empty_filter: !0,
        default_filter: null,
        optgroups: {},
        default_rule_flags: {filter_readonly: !1, operator_readonly: !1, value_readonly: !1, no_delete: !1},
        default_group_flags: {condition_readonly: !1, no_add_rule: !1, no_add_group: !1, no_delete: !1},
        templates: {group: null, rule: null, filterSelect: null, operatorSelect: null, ruleValueSelect: null},
        lang_code: "en",
        lang: {},
        operators: ["equal", "not_equal", "in", "not_in", "less", "less_or_equal", "greater", "greater_or_equal", "between", "not_between", "begins_with", "not_begins_with", "contains", "not_contains", "ends_with", "not_ends_with", "is_empty", "is_not_empty", "is_null", "is_not_null"],
        icons: {
            add_group: "glyphicon glyphicon-plus-sign",
            add_rule: "glyphicon glyphicon-plus",
            remove_group: "glyphicon glyphicon-remove",
            remove_rule: "glyphicon glyphicon-remove",
            error: "glyphicon glyphicon-warning-sign"
        }
    }, c.plugins = {}, c.defaults = function (e) {
        if ("object" != typeof e) return "string" == typeof e ? "object" == typeof c.DEFAULTS[e] ? $.extend(!0, {}, c.DEFAULTS[e]) : c.DEFAULTS[e] : $.extend(!0, {}, c.DEFAULTS);
        $.extendext(!0, "replace", c.DEFAULTS, e)
    }, c.define = function (e, t, r) {
        c.plugins[e] = {fct: t, def: r || {}}
    }, c.extend = function (e) {
        $.extend(c.prototype, e)
    }, c.prototype.initPlugins = function () {
        if (this.plugins) {
            if ($.isArray(this.plugins)) {
                var t = {};
                this.plugins.forEach(function (e) {
                    t[e] = null
                }), this.plugins = t
            }
            Object.keys(this.plugins).forEach(function (e) {
                e in c.plugins ? (this.plugins[e] = $.extend(!0, {}, c.plugins[e].def, this.plugins[e] || {}), c.plugins[e].fct.call(this, this.plugins[e])) : h.error("Config", 'Unable to find plugin "{0}"', e)
            }, this)
        }
    }, c.prototype.getPluginOptions = function (e, t) {
        var r;
        if (this.plugins && this.plugins[e] ? r = this.plugins[e] : c.plugins[e] && (r = c.plugins[e].def), r) return t ? r[t] : r;
        h.error("Config", 'Unable to find plugin "{0}"', e)
    }, c.prototype.init = function (e) {
        this.trigger("afterInit"), e ? (this.setRules(e), delete this.settings.rules) : this.setRoot(!0)
    }, c.prototype.checkFilters = function (e) {
        var t = [];
        if (e && 0 !== e.length || h.error("Config", "Missing filters list"), e.forEach(function (n, e) {
            switch (n.id || h.error("Config", "Missing filter {0} id", e), -1 != t.indexOf(n.id) && h.error("Config", 'Filter "{0}" already defined', n.id), t.push(n.id), n.type ? c.types[n.type] || h.error("Config", 'Invalid type "{0}"', n.type) : n.type = "string", n.input ? "function" != typeof n.input && -1 == c.inputs.indexOf(n.input) && h.error("Config", 'Invalid input "{0}"', n.input) : n.input = "number" === c.types[n.type] ? "number" : "text", n.operators && n.operators.forEach(function (e) {
                "string" != typeof e && h.error("Config", "Filter operators must be global operators types (string)")
            }), n.field || (n.field = n.id), n.label || (n.label = n.field), n.optgroup ? (this.status.has_optgroup = !0, this.settings.optgroups[n.optgroup] || (this.settings.optgroups[n.optgroup] = n.optgroup)) : n.optgroup = null, n.input) {
                case"radio":
                case"checkbox":
                    (!n.values || n.values.length < 1) && h.error("Config", 'Missing filter "{0}" values', n.id);
                    break;
                case"select":
                    var i = [];
                    n.has_optgroup = !1, h.iterateOptions(n.values, function (e, t, r) {
                        i.push({
                            value: e,
                            label: t,
                            optgroup: r || null
                        }), r && (n.has_optgroup = !0, this.settings.optgroups[r] || (this.settings.optgroups[r] = r))
                    }.bind(this)), n.has_optgroup ? n.values = h.groupSort(i, "optgroup") : n.values = i, n.placeholder && (void 0 === n.placeholder_value && (n.placeholder_value = -1), n.values.forEach(function (e) {
                        e.value == n.placeholder_value && h.error("Config", 'Placeholder of filter "{0}" overlaps with one of its values', n.id)
                    }))
            }
        }, this), this.settings.sort_filters) if ("function" == typeof this.settings.sort_filters) e.sort(this.settings.sort_filters); else {
            var r = this;
            e.sort(function (e, t) {
                return r.translate(e.label).localeCompare(r.translate(t.label))
            })
        }
        return this.status.has_optgroup && (e = h.groupSort(e, "optgroup")), e
    }, c.prototype.checkOperators = function (r) {
        var n = [];
        return r.forEach(function (e, t) {
            "string" == typeof e ? (c.OPERATORS[e] || h.error("Config", 'Unknown operator "{0}"', e), r[t] = e = $.extendext(!0, "replace", {}, c.OPERATORS[e])) : (e.type || h.error("Config", 'Missing "type" for operator {0}', t), c.OPERATORS[e.type] && (r[t] = e = $.extendext(!0, "replace", {}, c.OPERATORS[e.type], e)), void 0 !== e.nb_inputs && void 0 !== e.apply_to || h.error("Config", 'Missing "nb_inputs" and/or "apply_to" for operator "{0}"', e.type)), -1 != n.indexOf(e.type) && h.error("Config", 'Operator "{0}" already defined', e.type), n.push(e.type), e.optgroup ? (this.status.has_operator_optgroup = !0, this.settings.optgroups[e.optgroup] || (this.settings.optgroups[e.optgroup] = e.optgroup)) : e.optgroup = null
        }, this), this.status.has_operator_optgroup && (r = h.groupSort(r, "optgroup")), r
    }, c.prototype.bindEvents = function () {
        var o = this, t = c.selectors;
        this.$el.on("change.queryBuilder", t.group_condition, function () {
            if ($(this).is(":checked")) {
                var e = $(this).closest(t.group_container);
                o.getModel(e).condition = $(this).val()
            }
        }), this.$el.on("change.queryBuilder", t.rule_filter, function () {
            var e = $(this).closest(t.rule_container);
            o.getModel(e).filter = o.getFilterById($(this).val())
        }), this.$el.on("change.queryBuilder", t.rule_operator, function () {
            var e = $(this).closest(t.rule_container);
            o.getModel(e).operator = o.getOperatorByType($(this).val())
        }), this.$el.on("click.queryBuilder", t.add_rule, function () {
            var e = $(this).closest(t.group_container);
            o.addRule(o.getModel(e))
        }), this.$el.on("click.queryBuilder", t.delete_rule, function () {
            var e = $(this).closest(t.rule_container);
            o.deleteRule(o.getModel(e))
        }), 0 !== this.settings.allow_groups && (this.$el.on("click.queryBuilder", t.add_group, function () {
            var e = $(this).closest(t.group_container);
            o.addGroup(o.getModel(e))
        }), this.$el.on("click.queryBuilder", t.delete_group, function () {
            var e = $(this).closest(t.group_container);
            o.deleteGroup(o.getModel(e))
        })), this.model.on({
            drop: function (e, t) {
                t.$el.remove(), o.refreshGroupsConditions()
            }, add: function (e, t, r, n) {
                0 === n ? r.$el.prependTo(t.$el.find(">" + c.selectors.rules_list)) : r.$el.insertAfter(t.rules[n - 1].$el), o.refreshGroupsConditions()
            }, move: function (e, t, r, n) {
                t.$el.detach(), 0 === n ? t.$el.prependTo(r.$el.find(">" + c.selectors.rules_list)) : t.$el.insertAfter(r.rules[n - 1].$el), o.refreshGroupsConditions()
            }, update: function (e, t, r, n, i) {
                if (t instanceof l) switch (r) {
                    case"error":
                        o.updateError(t);
                        break;
                    case"flags":
                        o.applyRuleFlags(t);
                        break;
                    case"filter":
                        o.updateRuleFilter(t, i);
                        break;
                    case"operator":
                        o.updateRuleOperator(t, i);
                        break;
                    case"value":
                        o.updateRuleValue(t, i)
                } else switch (r) {
                    case"error":
                        o.updateError(t);
                        break;
                    case"flags":
                        o.applyGroupFlags(t);
                        break;
                    case"condition":
                        o.updateGroupCondition(t, i)
                }
            }
        })
    }, c.prototype.setRoot = function (e, t, r) {
        e = void 0 === e || !0 === e;
        var n = this.nextGroupId(), i = $(this.getGroupTemplate(n, 1));
        return this.$el.append(i), this.model.root = new a(null, i), this.model.root.model = this.model, this.model.root.data = t, this.model.root.flags = $.extend({}, this.settings.default_group_flags, r), this.model.root.condition = this.settings.default_condition, this.trigger("afterAddGroup", this.model.root), e && this.addRule(this.model.root), this.model.root
    }, c.prototype.addGroup = function (e, t, r, n) {
        t = void 0 === t || !0 === t;
        var i = e.level + 1;
        if (this.trigger("beforeAddGroup", e, t, i).isDefaultPrevented()) return null;
        var o = this.nextGroupId(), l = $(this.getGroupTemplate(o, i)), s = e.addGroup(l);
        return s.data = r, s.flags = $.extend({}, this.settings.default_group_flags, n), s.condition = this.settings.default_condition, this.trigger("afterAddGroup", s), this.trigger("rulesChanged"), t && this.addRule(s), s
    }, c.prototype.deleteGroup = function (e) {
        if (e.isRoot()) return !1;
        if (this.trigger("beforeDeleteGroup", e).isDefaultPrevented()) return !1;
        var t = !0;
        return e.each("reverse", function (e) {
            t &= this.deleteRule(e)
        }, function (e) {
            t &= this.deleteGroup(e)
        }, this), t && (e.drop(), this.trigger("afterDeleteGroup"), this.trigger("rulesChanged")), t
    }, c.prototype.updateGroupCondition = function (t, e) {
        t.$el.find(">" + c.selectors.group_condition).each(function () {
            var e = $(this);
            e.prop("checked", e.val() === t.condition), e.parent().toggleClass("active", e.val() === t.condition)
        }), this.trigger("afterUpdateGroupCondition", t, e), this.trigger("rulesChanged")
    }, c.prototype.refreshGroupsConditions = function () {
        !function t(e) {
            (!e.flags || e.flags && !e.flags.condition_readonly) && e.$el.find(">" + c.selectors.group_condition).prop("disabled", e.rules.length <= 1).parent().toggleClass("disabled", e.rules.length <= 1), e.each(null, function (e) {
                t(e)
            }, this)
        }(this.model.root)
    }, c.prototype.addRule = function (e, t, r) {
        if (this.trigger("beforeAddRule", e).isDefaultPrevented()) return null;
        var n = this.nextRuleId(), i = $(this.getRuleTemplate(n)), o = e.addRule(i);
        return o.data = t, o.flags = $.extend({}, this.settings.default_rule_flags, r), this.trigger("afterAddRule", o), this.trigger("rulesChanged"), this.createRuleFilters(o), !this.settings.default_filter && this.settings.display_empty_filter || (o.filter = this.change("getDefaultFilter", this.getFilterById(this.settings.default_filter || this.filters[0].id), o)), o
    }, c.prototype.deleteRule = function (e) {
        return !e.flags.no_delete && (!this.trigger("beforeDeleteRule", e).isDefaultPrevented() && (e.drop(), this.trigger("afterDeleteRule"), this.trigger("rulesChanged"), !0))
    }, c.prototype.createRuleFilters = function (e) {
        var t = this.change("getRuleFilters", this.filters, e), r = $(this.getRuleFilterSelect(e, t));
        e.$el.find(c.selectors.filter_container).html(r), this.trigger("afterCreateRuleFilters", e), this.applyRuleFlags(e)
    }, c.prototype.createRuleOperators = function (e) {
        var t = e.$el.find(c.selectors.operator_container).empty();
        if (e.filter) {
            var r = this.getOperators(e.filter), n = $(this.getRuleOperatorSelect(e, r));
            t.html(n), e.filter.default_operator ? e.__.operator = this.getOperatorByType(e.filter.default_operator) : e.__.operator = r[0], e.$el.find(c.selectors.rule_operator).val(e.operator.type), this.trigger("afterCreateRuleOperators", e, r), this.applyRuleFlags(e)
        }
    }, c.prototype.createRuleInput = function (e) {
        var t = e.$el.find(c.selectors.value_container).empty();
        if (e.__.value = void 0, e.filter && e.operator && 0 !== e.operator.nb_inputs) {
            for (var r = this, n = $(), i = e.filter, o = 0; o < e.operator.nb_inputs; o++) {
                var l = $(this.getRuleInput(e, o));
                0 < o && t.append(this.settings.inputs_separator), t.append(l), n = n.add(l)
            }
            t.css("display", ""), n.on("change " + (i.input_event || ""), function () {
                e._updating_input || (e._updating_value = !0, e.value = r.getRuleInputValue(e), e._updating_value = !1)
            }), i.plugin && n[i.plugin](i.plugin_config || {}), this.trigger("afterCreateRuleInput", e), void 0 !== i.default_value ? e.value = i.default_value : (e._updating_value = !0, e.value = r.getRuleInputValue(e), e._updating_value = !1), this.applyRuleFlags(e)
        }
    }, c.prototype.updateRuleFilter = function (e, t) {
        this.createRuleOperators(e), this.createRuleInput(e), e.$el.find(c.selectors.rule_filter).val(e.filter ? e.filter.id : "-1"), t && e.filter && t.id !== e.filter.id && (e.data = void 0), this.trigger("afterUpdateRuleFilter", e, t), this.trigger("rulesChanged")
    }, c.prototype.updateRuleOperator = function (e, t) {
        var r = e.$el.find(c.selectors.value_container);
        e.operator && 0 !== e.operator.nb_inputs ? (r.css("display", ""), !r.is(":empty") && t && e.operator.nb_inputs === t.nb_inputs && e.operator.optgroup === t.optgroup || this.createRuleInput(e)) : (r.hide(), e.__.value = void 0), e.operator && (e.$el.find(c.selectors.rule_operator).val(e.operator.type), e.__.value = this.getRuleInputValue(e)), this.trigger("afterUpdateRuleOperator", e, t), this.trigger("rulesChanged")
    }, c.prototype.updateRuleValue = function (e, t) {
        e._updating_value || this.setRuleInputValue(e, e.value), this.trigger("afterUpdateRuleValue", e, t), this.trigger("rulesChanged")
    }, c.prototype.applyRuleFlags = function (e) {
        var t = e.flags, r = c.selectors;
        e.$el.find(r.rule_filter).prop("disabled", t.filter_readonly), e.$el.find(r.rule_operator).prop("disabled", t.operator_readonly), e.$el.find(r.rule_value).prop("disabled", t.value_readonly), t.no_delete && e.$el.find(r.delete_rule).remove(), this.trigger("afterApplyRuleFlags", e)
    }, c.prototype.applyGroupFlags = function (e) {
        var t = e.flags, r = c.selectors;
        e.$el.find(">" + r.group_condition).prop("disabled", t.condition_readonly).parent().toggleClass("readonly", t.condition_readonly), t.no_add_rule && e.$el.find(r.add_rule).remove(), t.no_add_group && e.$el.find(r.add_group).remove(), t.no_delete && e.$el.find(r.delete_group).remove(), this.trigger("afterApplyGroupFlags", e)
    }, c.prototype.clearErrors = function (e) {
        (e = e || this.model.root) && (e.error = null, e instanceof a && e.each(function (e) {
            e.error = null
        }, function (e) {
            this.clearErrors(e)
        }, this))
    }, c.prototype.updateError = function (e) {
        if (this.settings.display_errors) if (null === e.error) e.$el.removeClass("has-error"); else {
            var t = this.translate("errors", e.error[0]);
            t = h.fmt(t, e.error.slice(1)), t = this.change("displayError", t, e.error, e), e.$el.addClass("has-error").find(c.selectors.error_container).eq(0).attr("title", t)
        }
    }, c.prototype.triggerValidationError = function (e, t, r) {
        $.isArray(t) || (t = [t]), this.trigger("validationError", e, t, r).isDefaultPrevented() || (e.error = t)
    }, c.prototype.destroy = function () {
        this.trigger("beforeDestroy"), this.status.generated_id && this.$el.removeAttr("id"), this.clear(), this.model = null, this.$el.off(".queryBuilder").removeClass("query-builder").removeData("queryBuilder"), delete this.$el[0].queryBuilder
    }, c.prototype.reset = function () {
        this.trigger("beforeReset").isDefaultPrevented() || (this.status.group_id = 1, this.status.rule_id = 0, this.model.root.empty(), this.model.root.data = void 0, this.model.root.flags = $.extend({}, this.settings.default_group_flags), this.model.root.condition = this.settings.default_condition, this.addRule(this.model.root), this.trigger("afterReset"), this.trigger("rulesChanged"))
    }, c.prototype.clear = function () {
        this.trigger("beforeClear").isDefaultPrevented() || (this.status.group_id = 0, this.status.rule_id = 0, this.model.root && (this.model.root.drop(), this.model.root = null), this.trigger("afterClear"), this.trigger("rulesChanged"))
    }, c.prototype.setOptions = function (e) {
        $.each(e, function (e, t) {
            -1 !== c.modifiable_options.indexOf(e) && (this.settings[e] = t)
        }.bind(this))
    }, c.prototype.getModel = function (e) {
        return e ? e instanceof i ? e : $(e).data("queryBuilderModel") : this.model.root
    }, c.prototype.validate = function (o) {
        o = $.extend({skip_empty: !1}, o), this.clearErrors();
        var l = this, e = function r(e) {
            var n = 0, i = 0;
            return e.each(function (e) {
                if (e.filter || !o.skip_empty) {
                    if (!e.filter) return l.triggerValidationError(e, "no_filter", null), void i++;
                    if (!e.operator) return l.triggerValidationError(e, "no_operator", null), void i++;
                    if (0 !== e.operator.nb_inputs) {
                        var t = l.validateValue(e, e.value);
                        if (!0 !== t) return l.triggerValidationError(e, t, e.value), void i++
                    }
                    n++
                }
            }, function (e) {
                var t = r(e);
                !0 === t ? n++ : !1 === t && i++
            }), !(0 < i) && (0 === n && !e.isRoot() && o.skip_empty ? null : !!(0 !== n || l.settings.allow_empty && e.isRoot()) || (l.triggerValidationError(e, "empty_group", null), !1))
        }(this.model.root);
        return this.change("validate", e)
    }, c.prototype.getRules = function (o) {
        o = $.extend({get_flags: !1, allow_invalid: !1, skip_empty: !1}, o);
        var e = this.validate(o);
        if (!e && !o.allow_invalid) return null;
        var l = this, t = function r(e) {
            var i = {condition: e.condition, rules: []};
            if (e.data && (i.data = $.extendext(!0, "replace", {}, e.data)), o.get_flags) {
                var t = l.getGroupFlags(e.flags, "all" === o.get_flags);
                $.isEmptyObject(t) || (i.flags = t)
            }
            return e.each(function (e) {
                if (e.filter || !o.skip_empty) {
                    var t = null;
                    e.operator && 0 === e.operator.nb_inputs || (t = e.value);
                    var r = {
                        id: e.filter ? e.filter.id : null,
                        field: e.filter ? e.filter.field : null,
                        type: e.filter ? e.filter.type : null,
                        input: e.filter ? e.filter.input : null,
                        operator: e.operator ? e.operator.type : null,
                        value: t
                    };
                    if ((e.filter && e.filter.data || e.data) && (r.data = $.extendext(!0, "replace", {}, e.filter.data, e.data)), o.get_flags) {
                        var n = l.getRuleFlags(e.flags, "all" === o.get_flags);
                        $.isEmptyObject(n) || (r.flags = n)
                    }
                    i.rules.push(l.change("ruleToJson", r, e))
                }
            }, function (e) {
                var t = r(e);
                0 === t.rules.length && o.skip_empty || i.rules.push(t)
            }, this), l.change("groupToJson", i, e)
        }(this.model.root);
        return t.valid = e, this.change("getRules", t)
    }, c.prototype.setRules = function (e, i) {
        i = $.extend({allow_invalid: !1}, i), $.isArray(e) && (e = {
            condition: this.settings.default_condition,
            rules: e
        }), e && e.rules && (0 !== e.rules.length || this.settings.allow_empty) || h.error("RulesParse", "Incorrect data object passed"), this.clear(), this.setRoot(!1, e.data, this.parseGroupFlags(e)), e = this.change("setRules", e, i);
        var o = this;
        !function r(e, n) {
            null !== n && (void 0 === e.condition ? e.condition = o.settings.default_condition : -1 == o.settings.conditions.indexOf(e.condition) && (h.error(!i.allow_invalid, "UndefinedCondition", 'Invalid condition "{0}"', e.condition), e.condition = o.settings.default_condition), n.condition = e.condition, e.rules.forEach(function (e) {
                var t;
                if (void 0 !== e.rules) if (-1 !== o.settings.allow_groups && o.settings.allow_groups < n.level) h.error(!i.allow_invalid, "RulesParse", "No more than {0} groups are allowed", o.settings.allow_groups), o.reset(); else {
                    if (null === (t = o.addGroup(n, !1, e.data, o.parseGroupFlags(e)))) return;
                    r(e, t)
                } else {
                    if (e.empty || (void 0 === e.id && (h.error(!i.allow_invalid, "RulesParse", "Missing rule field id"), e.empty = !0), void 0 === e.operator && (e.operator = "equal")), null === (t = o.addRule(n, e.data, o.parseRuleFlags(e)))) return;
                    e.empty || (t.filter = o.getFilterById(e.id, !i.allow_invalid)), t.filter && (t.operator = o.getOperatorByType(e.operator, !i.allow_invalid), t.operator || (t.operator = o.getOperators(t.filter)[0])), t.operator && 0 !== t.operator.nb_inputs && (void 0 !== e.value ? t.value = e.value : void 0 !== t.filter.default_value && (t.value = t.filter.default_value)), o.change("jsonToRule", t, e) != t && h.error("RulesParse", "Plugin tried to change rule reference")
                }
            }), o.change("jsonToGroup", n, e) != n && h.error("RulesParse", "Plugin tried to change group reference"))
        }(e, this.model.root), this.trigger("afterSetRules")
    }, c.prototype.validateValue = function (e, t) {
        var r = e.filter.validation || {}, n = !0;
        return n = r.callback ? r.callback.call(this, t, e) : this._validateValue(e, t), this.change("validateValue", n, t, e)
    }, c.prototype._validateValue = function (e, t) {
        var r, n, i = e.filter, o = e.operator, l = i.validation || {}, s = !0;
        1 === e.operator.nb_inputs && (t = [t]);
        for (var a = 0; a < o.nb_inputs; a++) {
            if (!o.multiple && $.isArray(t[a]) && 1 < t[a].length) {
                s = ["operator_not_multiple", o.type, this.translate("operators", o.type)];
                break
            }
            switch (i.input) {
                case"radio":
                    if (void 0 === t[a] || 0 === t[a].length) {
                        l.allow_empty_value || (s = ["radio_empty"]);
                        break
                    }
                    break;
                case"checkbox":
                    if (void 0 === t[a] || 0 === t[a].length) {
                        l.allow_empty_value || (s = ["checkbox_empty"]);
                        break
                    }
                    break;
                case"select":
                    if (void 0 === t[a] || 0 === t[a].length || i.placeholder && t[a] == i.placeholder_value) {
                        l.allow_empty_value || (s = ["select_empty"]);
                        break
                    }
                    break;
                default:
                    n = $.isArray(t[a]) ? t[a] : [t[a]];
                    for (var u = 0; u < n.length; u++) {
                        switch (c.types[i.type]) {
                            case"string":
                                if (void 0 === n[u] || 0 === n[u].length) {
                                    l.allow_empty_value || (s = ["string_empty"]);
                                    break
                                }
                                if (void 0 !== l.min && n[u].length < parseInt(l.min)) {
                                    s = [this.getValidationMessage(l, "min", "string_exceed_min_length"), l.min];
                                    break
                                }
                                if (void 0 !== l.max && n[u].length > parseInt(l.max)) {
                                    s = [this.getValidationMessage(l, "max", "string_exceed_max_length"), l.max];
                                    break
                                }
                                if (l.format && ("string" == typeof l.format && (l.format = new RegExp(l.format)), !l.format.test(n[u]))) {
                                    s = [this.getValidationMessage(l, "format", "string_invalid_format"), l.format];
                                    break
                                }
                                break;
                            case"number":
                                if (void 0 === n[u] || 0 === n[u].length) {
                                    l.allow_empty_value || (s = ["number_nan"]);
                                    break
                                }
                                if (isNaN(n[u])) {
                                    s = ["number_nan"];
                                    break
                                }
                                if ("integer" == i.type) {
                                    if (parseInt(n[u]) != n[u]) {
                                        s = ["number_not_integer"];
                                        break
                                    }
                                } else if (parseFloat(n[u]) != n[u]) {
                                    s = ["number_not_double"];
                                    break
                                }
                                if (void 0 !== l.min && n[u] < parseFloat(l.min)) {
                                    s = [this.getValidationMessage(l, "min", "number_exceed_min"), l.min];
                                    break
                                }
                                if (void 0 !== l.max && n[u] > parseFloat(l.max)) {
                                    s = [this.getValidationMessage(l, "max", "number_exceed_max"), l.max];
                                    break
                                }
                                if (void 0 !== l.step && "any" !== l.step) {
                                    var p = (n[u] / l.step).toPrecision(14);
                                    if (parseInt(p) != p) {
                                        s = [this.getValidationMessage(l, "step", "number_wrong_step"), l.step];
                                        break
                                    }
                                }
                                break;
                            case"datetime":
                                if (void 0 === n[u] || 0 === n[u].length) {
                                    l.allow_empty_value || (s = ["datetime_empty"]);
                                    break
                                }
                                if (l.format) {
                                    "moment" in window || h.error("MissingLibrary", "MomentJS is required for Date/Time validation. Get it here http://momentjs.com");
                                    var d = moment(n[u], l.format);
                                    if (!d.isValid()) {
                                        s = [this.getValidationMessage(l, "format", "datetime_invalid"), l.format];
                                        break
                                    }
                                    if (l.min && d < moment(l.min, l.format)) {
                                        s = [this.getValidationMessage(l, "min", "datetime_exceed_min"), l.min];
                                        break
                                    }
                                    if (l.max && d > moment(l.max, l.format)) {
                                        s = [this.getValidationMessage(l, "max", "datetime_exceed_max"), l.max];
                                        break
                                    }
                                }
                                break;
                            case"boolean":
                                if (void 0 === n[u] || 0 === n[u].length) {
                                    l.allow_empty_value || (s = ["boolean_not_valid"]);
                                    break
                                }
                                if ("true" !== (r = ("" + n[u]).trim().toLowerCase()) && "false" !== r && "1" !== r && "0" !== r && 1 !== n[u] && 0 !== n[u]) {
                                    s = ["boolean_not_valid"];
                                    break
                                }
                        }
                        if (!0 !== s) break
                    }
            }
            if (!0 !== s) break
        }
        if (("between" === e.operator.type || "not_between" === e.operator.type) && 2 === t.length) switch (c.types[i.type]) {
            case"number":
                t[0] > t[1] && (s = ["number_between_invalid", t[0], t[1]]);
                break;
            case"datetime":
                l.format && ("moment" in window || h.error("MissingLibrary", "MomentJS is required for Date/Time validation. Get it here http://momentjs.com"), moment(t[0], l.format).isAfter(moment(t[1], l.format)) && (s = ["datetime_between_invalid", t[0], t[1]]))
        }
        return s
    }, c.prototype.nextGroupId = function () {
        return this.status.id + "_group_" + this.status.group_id++
    }, c.prototype.nextRuleId = function () {
        return this.status.id + "_rule_" + this.status.rule_id++
    }, c.prototype.getOperators = function (r) {
        "string" == typeof r && (r = this.getFilterById(r));
        for (var e = [], t = 0, n = this.operators.length; t < n; t++) {
            if (r.operators) {
                if (-1 == r.operators.indexOf(this.operators[t].type)) continue
            } else if (-1 == this.operators[t].apply_to.indexOf(c.types[r.type])) continue;
            e.push(this.operators[t])
        }
        return r.operators && e.sort(function (e, t) {
            return r.operators.indexOf(e.type) - r.operators.indexOf(t.type)
        }), this.change("getOperators", e, r)
    }, c.prototype.getFilterById = function (e, t) {
        if ("-1" == e) return null;
        for (var r = 0, n = this.filters.length; r < n; r++) if (this.filters[r].id == e) return this.filters[r];
        return h.error(!1 !== t, "UndefinedFilter", 'Undefined filter "{0}"', e), null
    }, c.prototype.getOperatorByType = function (e, t) {
        if ("-1" == e) return null;
        for (var r = 0, n = this.operators.length; r < n; r++) if (this.operators[r].type == e) return this.operators[r];
        return h.error(!1 !== t, "UndefinedOperator", 'Undefined operator "{0}"', e), null
    }, c.prototype.getRuleInputValue = function (e) {
        var t = e.filter, r = e.operator, n = [];
        if (t.valueGetter) n = t.valueGetter.call(this, e); else {
            for (var i = e.$el.find(c.selectors.value_container), o = 0; o < r.nb_inputs; o++) {
                var l, s = h.escapeElementId(e.id + "_value_" + o);
                switch (t.input) {
                    case"radio":
                        n.push(i.find("[name=" + s + "]:checked").val());
                        break;
                    case"checkbox":
                        l = [], i.find("[name=" + s + "]:checked").each(function () {
                            l.push($(this).val())
                        }), n.push(l);
                        break;
                    case"select":
                        t.multiple ? (l = [], i.find("[name=" + s + "] option:selected").each(function () {
                            l.push($(this).val())
                        }), n.push(l)) : n.push(i.find("[name=" + s + "] option:selected").val());
                        break;
                    default:
                        n.push(i.find("[name=" + s + "]").val())
                }
            }
            n = n.map(function (e) {
                return r.multiple && t.value_separator && "string" == typeof e && (e = e.split(t.value_separator)), $.isArray(e) ? e.map(function (e) {
                    return h.changeType(e, t.type)
                }) : h.changeType(e, t.type)
            }), 1 === r.nb_inputs && (n = n[0]), t.valueParser && (n = t.valueParser.call(this, e, n))
        }
        return this.change("getRuleValue", n, e)
    }, c.prototype.setRuleInputValue = function (e, t) {
        var r = e.filter, n = e.operator;
        if (r && n) {
            if (e._updating_input = !0, r.valueSetter) r.valueSetter.call(this, e, t); else {
                var i = e.$el.find(c.selectors.value_container);
                1 == n.nb_inputs && (t = [t]);
                for (var o = 0; o < n.nb_inputs; o++) {
                    var l = h.escapeElementId(e.id + "_value_" + o);
                    switch (r.input) {
                        case"radio":
                            i.find("[name=" + l + '][value="' + t[o] + '"]').prop("checked", !0).trigger("change");
                            break;
                        case"checkbox":
                            $.isArray(t[o]) || (t[o] = [t[o]]), t[o].forEach(function (e) {
                                i.find("[name=" + l + '][value="' + e + '"]').prop("checked", !0).trigger("change")
                            });
                            break;
                        default:
                            n.multiple && r.value_separator && $.isArray(t[o]) && (t[o] = t[o].join(r.value_separator)), i.find("[name=" + l + "]").val(t[o]).trigger("change")
                    }
                }
            }
            e._updating_input = !1
        }
    }, c.prototype.parseRuleFlags = function (e) {
        var t = $.extend({}, this.settings.default_rule_flags);
        return e.readonly && $.extend(t, {
            filter_readonly: !0,
            operator_readonly: !0,
            value_readonly: !0,
            no_delete: !0
        }), e.flags && $.extend(t, e.flags), this.change("parseRuleFlags", t, e)
    }, c.prototype.getRuleFlags = function (r, e) {
        if (e) return $.extend({}, r);
        var n = {};
        return $.each(this.settings.default_rule_flags, function (e, t) {
            r[e] !== t && (n[e] = r[e])
        }), n
    }, c.prototype.parseGroupFlags = function (e) {
        var t = $.extend({}, this.settings.default_group_flags);
        return e.readonly && $.extend(t, {
            condition_readonly: !0,
            no_add_rule: !0,
            no_add_group: !0,
            no_delete: !0
        }), e.flags && $.extend(t, e.flags), this.change("parseGroupFlags", t, e)
    }, c.prototype.getGroupFlags = function (r, e) {
        if (e) return $.extend({}, r);
        var n = {};
        return $.each(this.settings.default_group_flags, function (e, t) {
            r[e] !== t && (n[e] = r[e])
        }), n
    }, c.prototype.translate = function (e, t) {
        var r;
        return t || (t = e, e = void 0), r = "object" == typeof t ? t[this.settings.lang_code] || t.en : (e ? this.lang[e] : this.lang)[t] || t, this.change("translate", r, t, e)
    }, c.prototype.getValidationMessage = function (e, t, r) {
        return e.messages && e.messages[t] || r
    }, c.templates.group = '<div id="{{= it.group_id }}" class="rules-group-container">   <div class="rules-group-header">     <div class="btn-group pull-right group-actions">       <button type="button" class="btn btn-xs btn-success" data-add="rule">         <i class="{{= it.icons.add_rule }}"></i> {{= it.translate("add_rule") }}       </button>       {{? it.settings.allow_groups===-1 || it.settings.allow_groups>=it.level }}         <button type="button" class="btn btn-xs btn-success" data-add="group">           <i class="{{= it.icons.add_group }}"></i> {{= it.translate("add_group") }}         </button>       {{?}}       {{? it.level>1 }}         <button type="button" class="btn btn-xs btn-danger" data-delete="group">           <i class="{{= it.icons.remove_group }}"></i> {{= it.translate("delete_group") }}         </button>       {{?}}     </div>     <div class="btn-group group-conditions">       {{~ it.conditions: condition }}         <label class="btn btn-xs btn-primary">           <input type="radio" name="{{= it.group_id }}_cond" value="{{= condition }}"> {{= it.translate("conditions", condition) }}         </label>       {{~}}     </div>     {{? it.settings.display_errors }}       <div class="error-container"><i class="{{= it.icons.error }}"></i></div>     {{?}}   </div>   <div class=rules-group-body>     <div class=rules-list></div>   </div> </div>', c.templates.rule = '<div id="{{= it.rule_id }}" class="rule-container">   <div class="rule-header">     <div class="btn-group pull-right rule-actions">       <button type="button" class="btn btn-xs btn-danger" data-delete="rule">         <i class="{{= it.icons.remove_rule }}"></i> {{= it.translate("delete_rule") }}       </button>     </div>   </div>   {{? it.settings.display_errors }}     <div class="error-container"><i class="{{= it.icons.error }}"></i></div>   {{?}}   <div class="rule-filter-container"></div>   <div class="rule-operator-container"></div>   <div class="rule-value-container"></div> </div>', c.templates.filterSelect = '{{ var optgroup = null; }} <select class="form-control" name="{{= it.rule.id }}_filter">   {{? it.settings.display_empty_filter }}     <option value="-1">{{= it.settings.select_placeholder }}</option>   {{?}}   {{~ it.filters: filter }}     {{? optgroup !== filter.optgroup }}       {{? optgroup !== null }}</optgroup>{{?}}       {{? (optgroup = filter.optgroup) !== null }}         <optgroup label="{{= it.translate(it.settings.optgroups[optgroup]) }}">       {{?}}     {{?}}     <option value="{{= filter.id }}" {{? filter.icon}}data-icon="{{= filter.icon}}"{{?}}>{{= it.translate(filter.label) }}</option>   {{~}}   {{? optgroup !== null }}</optgroup>{{?}} </select>', c.templates.operatorSelect = '{{? it.operators.length === 1 }} <span> {{= it.translate("operators", it.operators[0].type) }} </span> {{?}} {{ var optgroup = null; }} <select class="form-control {{? it.operators.length === 1 }}hide{{?}}" name="{{= it.rule.id }}_operator">   {{~ it.operators: operator }}     {{? optgroup !== operator.optgroup }}       {{? optgroup !== null }}</optgroup>{{?}}       {{? (optgroup = operator.optgroup) !== null }}         <optgroup label="{{= it.translate(it.settings.optgroups[optgroup]) }}">       {{?}}     {{?}}     <option value="{{= operator.type }}" {{? operator.icon}}data-icon="{{= operator.icon}}"{{?}}>{{= it.translate("operators", operator.type) }}</option>   {{~}}   {{? optgroup !== null }}</optgroup>{{?}} </select>', c.templates.ruleValueSelect = '{{ var optgroup = null; }} <select class="form-control" name="{{= it.name }}" {{? it.rule.filter.multiple }}multiple{{?}}>   {{? it.rule.filter.placeholder }}     <option value="{{= it.rule.filter.placeholder_value }}" disabled selected>{{= it.rule.filter.placeholder }}</option>   {{?}}   {{~ it.rule.filter.values: entry }}     {{? optgroup !== entry.optgroup }}       {{? optgroup !== null }}</optgroup>{{?}}       {{? (optgroup = entry.optgroup) !== null }}         <optgroup label="{{= it.translate(it.settings.optgroups[optgroup]) }}">       {{?}}     {{?}}     <option value="{{= entry.value }}">{{= entry.label }}</option>   {{~}}   {{? optgroup !== null }}</optgroup>{{?}} </select>', c.prototype.getGroupTemplate = function (e, t) {
        var r = this.templates.group({
            builder: this,
            group_id: e,
            level: t,
            conditions: this.settings.conditions,
            icons: this.icons,
            settings: this.settings,
            translate: this.translate.bind(this)
        });
        return this.change("getGroupTemplate", r, t)
    }, c.prototype.getRuleTemplate = function (e) {
        var t = this.templates.rule({
            builder: this,
            rule_id: e,
            icons: this.icons,
            settings: this.settings,
            translate: this.translate.bind(this)
        });
        return this.change("getRuleTemplate", t)
    }, c.prototype.getRuleFilterSelect = function (e, t) {
        var r = this.templates.filterSelect({
            builder: this,
            rule: e,
            filters: t,
            icons: this.icons,
            settings: this.settings,
            translate: this.translate.bind(this)
        });
        return this.change("getRuleFilterSelect", r, e, t)
    }, c.prototype.getRuleOperatorSelect = function (e, t) {
        var r = this.templates.operatorSelect({
            builder: this,
            rule: e,
            operators: t,
            icons: this.icons,
            settings: this.settings,
            translate: this.translate.bind(this)
        });
        return this.change("getRuleOperatorSelect", r, e, t)
    }, c.prototype.getRuleValueSelect = function (e, t) {
        var r = this.templates.ruleValueSelect({
            builder: this,
            name: e,
            rule: t,
            icons: this.icons,
            settings: this.settings,
            translate: this.translate.bind(this)
        });
        return this.change("getRuleValueSelect", r, e, t)
    }, c.prototype.getRuleInput = function (e, t) {
        var r = e.filter, n = e.filter.validation || {}, i = e.id + "_value_" + t, o = r.vertical ? " class=block" : "",
            l = "";
        if ("function" == typeof r.input) l = r.input.call(this, e, i); else switch (r.input) {
            case"radio":
            case"checkbox":
                h.iterateOptions(r.values, function (e, t) {
                    l += "<label" + o + '><input type="' + r.input + '" name="' + i + '" value="' + e + '"> ' + t + "</label> "
                });
                break;
            case"select":
                l = this.getRuleValueSelect(i, e);
                break;
            case"textarea":
                l += '<textarea class="form-control" name="' + i + '"', r.size && (l += ' cols="' + r.size + '"'), r.rows && (l += ' rows="' + r.rows + '"'), void 0 !== n.min && (l += ' minlength="' + n.min + '"'), void 0 !== n.max && (l += ' maxlength="' + n.max + '"'), r.placeholder && (l += ' placeholder="' + r.placeholder + '"'), l += "></textarea>";
                break;
            case"number":
                l += '<input class="form-control" type="number" name="' + i + '"', void 0 !== n.step && (l += ' step="' + n.step + '"'), void 0 !== n.min && (l += ' min="' + n.min + '"'), void 0 !== n.max && (l += ' max="' + n.max + '"'), r.placeholder && (l += ' placeholder="' + r.placeholder + '"'), r.size && (l += ' size="' + r.size + '"'), l += ">";
                break;
            default:
                l += '<input class="form-control" type="text" name="' + i + '"', r.placeholder && (l += ' placeholder="' + r.placeholder + '"'), "string" === r.type && void 0 !== n.min && (l += ' minlength="' + n.min + '"'), "string" === r.type && void 0 !== n.max && (l += ' maxlength="' + n.max + '"'), r.size && (l += ' size="' + r.size + '"'), l += ">"
        }
        return this.change("getRuleInput", l, e, i)
    };
    var h = {};

    function n() {
        this.root = null, this.$ = $(this)
    }

    (c.utils = h).iterateOptions = function (e, r) {
        e && ($.isArray(e) ? e.forEach(function (e) {
            $.isPlainObject(e) ? "value" in e ? r(e.value, e.label || e.value, e.optgroup) : $.each(e, function (e, t) {
                return r(e, t), !1
            }) : r(e, e)
        }) : $.each(e, function (e, t) {
            r(e, t)
        }))
    }, h.fmt = function (e, r) {
        return Array.isArray(r) || (r = Array.prototype.slice.call(arguments, 1)), e.replace(/{([0-9]+)}/g, function (e, t) {
            return r[parseInt(t)]
        })
    }, h.error = function () {
        var e = 0, t = "boolean" != typeof arguments[e] || arguments[e++], r = arguments[e++], n = arguments[e++],
            i = Array.isArray(arguments[e]) ? arguments[e] : Array.prototype.slice.call(arguments, e);
        if (t) {
            var o = new Error(h.fmt(n, i));
            throw o.name = r + "Error", o.args = i, o
        }
        console.error(r + "Error: " + h.fmt(n, i))
    }, h.changeType = function (e, t) {
        if ("" !== e && void 0 !== e) switch (t) {
            case"integer":
                return "string" != typeof e || /^-?\d+$/.test(e) ? parseInt(e) : e;
            case"double":
                return "string" != typeof e || /^-?\d+\.?\d*$/.test(e) ? parseFloat(e) : e;
            case"boolean":
                return "string" != typeof e || /^(0|1|true|false){1}$/i.test(e) ? !0 === e || 1 === e || "true" === e.toLowerCase() || "1" === e : e;
            default:
                return e
        }
    }, h.escapeString = function (e) {
        return "string" != typeof e ? e : e.replace(/[\0\n\r\b\\\'\"]/g, function (e) {
            switch (e) {
                case"\0":
                    return "\\0";
                case"\n":
                    return "\\n";
                case"\r":
                    return "\\r";
                case"\b":
                    return "\\b";
                default:
                    return "\\" + e
            }
        }).replace(/\t/g, "\\t").replace(/\x1a/g, "\\Z")
    }, h.escapeRegExp = function (e) {
        return e.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&")
    }, h.escapeElementId = function (e) {
        return e ? e.replace(/(\\)?([:.\[\],])/g, function (e, t, r) {
            return t ? e : "\\" + r
        }) : e
    }, h.groupSort = function (e, r) {
        var n = [], i = [];
        return e.forEach(function (e) {
            var t;
            e[r] ? -1 == (t = n.lastIndexOf(e[r])) ? t = n.length : t++ : t = n.length, n.splice(t, 0, e[r]), i.splice(t, 0, e)
        }), i
    }, h.defineModelProperties = function (e, t) {
        t.forEach(function (r) {
            Object.defineProperty(e.prototype, r, {
                enumerable: !0, get: function () {
                    return this.__[r]
                }, set: function (e) {
                    var t = null !== this.__[r] && "object" == typeof this.__[r] ? $.extend({}, this.__[r]) : this.__[r];
                    this.__[r] = e, null !== this.model && this.model.trigger("update", this, r, e, t)
                }
            })
        })
    }, $.extend(n.prototype, {
        trigger: function (e) {
            var t = new $.Event(e);
            return this.$.triggerHandler(t, Array.prototype.slice.call(arguments, 1)), t
        }, on: function () {
            return this.$.on.apply(this.$, Array.prototype.slice.call(arguments)), this
        }, off: function () {
            return this.$.off.apply(this.$, Array.prototype.slice.call(arguments)), this
        }, once: function () {
            return this.$.one.apply(this.$, Array.prototype.slice.call(arguments)), this
        }
    });
    var i = function (e, t) {
        if (!(this instanceof i)) return new i(e, t);
        Object.defineProperty(this, "__", {value: {}}), t.data("queryBuilderModel", this), this.__.level = 1, this.__.error = null, this.__.flags = {}, this.__.data = void 0, this.$el = t, this.id = t[0].id, this.model = null, this.parent = e
    };
    h.defineModelProperties(i, ["level", "error", "data", "flags"]), Object.defineProperty(i.prototype, "parent", {
        enumerable: !0,
        get: function () {
            return this.__.parent
        },
        set: function (e) {
            this.__.parent = e, this.level = null === e ? 1 : e.level + 1, this.model = null === e ? null : e.model
        }
    }), i.prototype.isRoot = function () {
        return 1 === this.level
    }, i.prototype.getPos = function () {
        return this.isRoot() ? -1 : this.parent.getNodePos(this)
    }, i.prototype.drop = function () {
        var e = this.model;
        this.parent && this.parent.removeNode(this), this.$el.removeData("queryBuilderModel"), null !== e && e.trigger("drop", this)
    }, i.prototype.moveAfter = function (e) {
        this.isRoot() || this.move(e.parent, e.getPos() + 1)
    }, i.prototype.moveAtBegin = function (e) {
        this.isRoot() || (void 0 === e && (e = this.parent), this.move(e, 0))
    }, i.prototype.moveAtEnd = function (e) {
        this.isRoot() || (void 0 === e && (e = this.parent), this.move(e, 0 === e.length() ? 0 : e.length() - 1))
    }, i.prototype.move = function (e, t) {
        this.isRoot() || ("number" == typeof e && (t = e, e = this.parent), this.parent.removeNode(this), e.insertNode(this, t, !1), null !== this.model && this.model.trigger("move", this, e, t))
    };
    var a = function (e, t) {
        if (!(this instanceof a)) return new a(e, t);
        i.call(this, e, t), this.rules = [], this.__.condition = null
    };
    a.prototype = Object.create(i.prototype), a.prototype.constructor = a, h.defineModelProperties(a, ["condition"]), a.prototype.empty = function () {
        this.each("reverse", function (e) {
            e.drop()
        }, function (e) {
            e.drop()
        })
    }, a.prototype.drop = function () {
        this.empty(), i.prototype.drop.call(this)
    }, a.prototype.length = function () {
        return this.rules.length
    }, a.prototype.insertNode = function (e, t, r) {
        return void 0 === t && (t = this.length()), this.rules.splice(t, 0, e), e.parent = this, r && null !== this.model && this.model.trigger("add", this, e, t), e
    }, a.prototype.addGroup = function (e, t) {
        return this.insertNode(new a(this, e), t, !0)
    }, a.prototype.addRule = function (e, t) {
        return this.insertNode(new l(this, e), t, !0)
    }, a.prototype.removeNode = function (e) {
        var t = this.getNodePos(e);
        -1 !== t && (e.parent = null, this.rules.splice(t, 1))
    }, a.prototype.getNodePos = function (e) {
        return this.rules.indexOf(e)
    }, a.prototype.each = function (e, t, r, n) {
        "boolean" != typeof e && "string" != typeof e && (n = r, r = t, t = e, e = !1), n = void 0 === n ? null : n;
        for (var i = e ? this.rules.length - 1 : 0, o = e ? 0 : this.rules.length - 1, l = e ? -1 : 1, s = !1; (e ? o <= i : i <= o) && (this.rules[i] instanceof a ? r && (s = !1 === r.call(n, this.rules[i])) : t && (s = !1 === t.call(n, this.rules[i])), !s); i += l) ;
        return !s
    }, a.prototype.contains = function (t, e) {
        return -1 !== this.getNodePos(t) || !!e && !this.each(function () {
            return !0
        }, function (e) {
            return !e.contains(t, !0)
        })
    };
    var l = function (e, t) {
        if (!(this instanceof l)) return new l(e, t);
        i.call(this, e, t), this._updating_value = !1, this._updating_input = !1, this.__.filter = null, this.__.operator = null, this.__.value = void 0
    };

    function u(e, t, r) {
        var n, i, o = c.selectors;
        (n = t.closest(o.rule_container)).length && (i = "moveAfter"), i || (n = t.closest(o.group_header)).length && (n = t.closest(o.group_container), i = "moveAtBegin"), i || (n = t.closest(o.group_container)).length && (i = "moveAtEnd"), i && (e[i](r.getModel(n)), r && e instanceof l && r.setRuleInputValue(e, e.value))
    }

    function o(e) {
        var t = e.match(/(question_mark|numbered|named)(?:\((.)\))?/);
        return t || (t = [null, "question_mark", void 0]), t
    }

    return l.prototype = Object.create(i.prototype), l.prototype.constructor = l, h.defineModelProperties(l, ["filter", "operator", "value"]), l.prototype.isRoot = function () {
        return !1
    }, c.Group = a, c.Rule = l, $.fn.queryBuilder = function (e) {
        0 === this.length && h.error("Config", "No target defined"), 1 < this.length && h.error("Config", "Unable to initialize on multiple target");
        var t = this.data("queryBuilder"), r = "object" == typeof e && e || {};
        if (!t && "destroy" == e) return this;
        if (!t) {
            var n = new c(this, r);
            this.data("queryBuilder", n), n.init(r.rules)
        }
        return "string" == typeof e ? t[e].apply(t, Array.prototype.slice.call(arguments, 1)) : this
    }, $.fn.queryBuilder.constructor = c, $.fn.queryBuilder.defaults = c.defaults, $.fn.queryBuilder.extend = c.extend, $.fn.queryBuilder.define = c.define, $.fn.queryBuilder.regional = c.regional, c.define("bt-checkbox", function (u) {
        "glyphicons" == u.font && this.$el.addClass("bt-checkbox-glyphicons"), this.on("getRuleInput.filter", function (i, e, o) {
            var l = e.filter;
            if (("radio" === l.input || "checkbox" === l.input) && !l.plugin) {
                i.value = "", l.colors || (l.colors = {}), l.color && (l.colors._def_ = l.color);
                var s = l.vertical ? ' style="display:block"' : "", a = 0;
                h.iterateOptions(l.values, function (e, t) {
                    var r = l.colors[e] || l.colors._def_ || u.color, n = o + "_" + a++;
                    i.value += "<div" + s + ' class="' + l.input + " " + l.input + "-" + r + '">   <input type="' + l.input + '" name="' + o + '" id="' + n + '" value="' + e + '">   <label for="' + n + '">' + t + "</label> </div>"
                })
            }
        })
    }, {font: "glyphicons", color: "default"}), c.define("bt-selectpicker", function (r) {
        $.fn.selectpicker && $.fn.selectpicker.Constructor || h.error("MissingLibrary", 'Bootstrap Select is required to use "bt-selectpicker" plugin. Get it here: http://silviomoreto.github.io/bootstrap-select');
        var n = c.selectors;
        this.on("afterCreateRuleFilters", function (e, t) {
            t.$el.find(n.rule_filter).removeClass("form-control").selectpicker(r)
        }), this.on("afterCreateRuleOperators", function (e, t) {
            t.$el.find(n.rule_operator).removeClass("form-control").selectpicker(r)
        }), this.on("afterUpdateRuleFilter", function (e, t) {
            t.$el.find(n.rule_filter).selectpicker("render")
        }), this.on("afterUpdateRuleOperator", function (e, t) {
            t.$el.find(n.rule_operator).selectpicker("render")
        }), this.on("beforeDeleteRule", function (e, t) {
            t.$el.find(n.rule_filter).selectpicker("destroy"), t.$el.find(n.rule_operator).selectpicker("destroy")
        })
    }, {
        container: "body",
        style: "btn-inverse btn-xs",
        width: "auto",
        showIcon: !1
    }), c.define("bt-tooltip-errors", function (n) {
        $.fn.tooltip && $.fn.tooltip.Constructor && $.fn.tooltip.Constructor.prototype.fixTitle || h.error("MissingLibrary", 'Bootstrap Tooltip is required to use "bt-tooltip-errors" plugin. Get it here: http://getbootstrap.com');
        var i = this;
        this.on("getRuleTemplate.filter getGroupTemplate.filter", function (e) {
            var t = $(e.value);
            t.find(c.selectors.error_container).attr("data-toggle", "tooltip"), e.value = t.prop("outerHTML")
        }), this.model.on("update", function (e, t, r) {
            "error" == r && i.settings.display_errors && t.$el.find(c.selectors.error_container).eq(0).tooltip(n).tooltip("hide").tooltip("fixTitle")
        })
    }, {placement: "right"}), c.extend({
        setFilters: function (e, t) {
            var r = this;
            void 0 === t && (t = e, e = !1), t = this.checkFilters(t);
            var n = (t = this.change("setFilters", t)).map(function (e) {
                return e.id
            });
            if (e || function e(t) {
                t.each(function (e) {
                    e.filter && -1 === n.indexOf(e.filter.id) && h.error("ChangeFilter", 'A rule is using filter "{0}"', e.filter.id)
                }, e)
            }(this.model.root), this.filters = t, function e(t) {
                t.each(!0, function (e) {
                    e.filter && -1 === n.indexOf(e.filter.id) ? (e.drop(), r.trigger("rulesChanged")) : (r.createRuleFilters(e), e.$el.find(c.selectors.rule_filter).val(e.filter ? e.filter.id : "-1"), r.trigger("afterUpdateRuleFilter", e))
                }, e)
            }(this.model.root), this.settings.plugins && (this.settings.plugins["unique-filter"] && this.updateDisabledFilters(), this.settings.plugins["bt-selectpicker"] && this.$el.find(c.selectors.rule_filter).selectpicker("render")), this.settings.default_filter) try {
                this.getFilterById(this.settings.default_filter)
            } catch (e) {
                this.settings.default_filter = null
            }
            this.trigger("afterSetFilters", t)
        }, addFilter: function (e, r) {
            void 0 === r || "#end" == r ? r = this.filters.length : "#start" == r && (r = 0), $.isArray(e) || (e = [e]);
            var t = $.extend(!0, [], this.filters);
            parseInt(r) == r ? Array.prototype.splice.apply(t, [r, 0].concat(e)) : this.filters.some(function (e, t) {
                if (e.id == r) return r = t + 1, !0
            }) ? Array.prototype.splice.apply(t, [r, 0].concat(e)) : Array.prototype.push.apply(t, e), this.setFilters(t)
        }, removeFilter: function (t, e) {
            var r = $.extend(!0, [], this.filters);
            "string" == typeof t && (t = [t]), r = r.filter(function (e) {
                return -1 === t.indexOf(e.id)
            }), this.setFilters(e, r)
        }
    }), c.define("chosen-selectpicker", function (r) {
        $.fn.chosen || h.error("MissingLibrary", 'chosen is required to use "chosen-selectpicker" plugin. Get it here: https://github.com/harvesthq/chosen'), this.settings.plugins["bt-selectpicker"] && h.error("Conflict", "bt-selectpicker is already selected as the dropdown plugin. Please remove chosen-selectpicker from the plugin list");
        var n = c.selectors;
        this.on("afterCreateRuleFilters", function (e, t) {
            t.$el.find(n.rule_filter).removeClass("form-control").chosen(r)
        }), this.on("afterCreateRuleOperators", function (e, t) {
            t.$el.find(n.rule_operator).removeClass("form-control").chosen(r)
        }), this.on("afterUpdateRuleFilter", function (e, t) {
            t.$el.find(n.rule_filter).trigger("chosen:updated")
        }), this.on("afterUpdateRuleOperator", function (e, t) {
            t.$el.find(n.rule_operator).trigger("chosen:updated")
        }), this.on("beforeDeleteRule", function (e, t) {
            t.$el.find(n.rule_filter).chosen("destroy"), t.$el.find(n.rule_operator).chosen("destroy")
        })
    }), c.define("filter-description", function (i) {
        "inline" === i.mode ? this.on("afterUpdateRuleFilter afterUpdateRuleOperator", function (e, t) {
            var r = t.$el.find("p.filter-description"), n = e.builder.getFilterDescription(t.filter, t);
            n ? (0 === r.length ? (r = $('<p class="filter-description"></p>')).appendTo(t.$el) : r.css("display", ""), r.html('<i class="' + i.icon + '"></i> ' + n)) : r.hide()
        }) : "popover" === i.mode ? ($.fn.popover && $.fn.popover.Constructor && $.fn.popover.Constructor.prototype.fixTitle || h.error("MissingLibrary", 'Bootstrap Popover is required to use "filter-description" plugin. Get it here: http://getbootstrap.com'), this.on("afterUpdateRuleFilter afterUpdateRuleOperator", function (e, t) {
            var r = t.$el.find("button.filter-description"), n = e.builder.getFilterDescription(t.filter, t);
            n ? (0 === r.length ? ((r = $('<button type="button" class="btn btn-xs btn-info filter-description" data-toggle="popover"><i class="' + i.icon + '"></i></button>')).prependTo(t.$el.find(c.selectors.rule_actions)), r.popover({
                placement: "left",
                container: "body",
                html: !0
            }), r.on("mouseout", function () {
                r.popover("hide")
            })) : r.css("display", ""), r.data("bs.popover").options.content = n, r.attr("aria-describedby") && r.popover("show")) : (r.hide(), r.data("bs.popover") && r.popover("hide"))
        })) : "bootbox" === i.mode && ("bootbox" in window || h.error("MissingLibrary", 'Bootbox is required to use "filter-description" plugin. Get it here: http://bootboxjs.com'), this.on("afterUpdateRuleFilter afterUpdateRuleOperator", function (e, t) {
            var r = t.$el.find("button.filter-description"), n = e.builder.getFilterDescription(t.filter, t);
            n ? (0 === r.length ? ((r = $('<button type="button" class="btn btn-xs btn-info filter-description" data-toggle="bootbox"><i class="' + i.icon + '"></i></button>')).prependTo(t.$el.find(c.selectors.rule_actions)), r.on("click", function () {
                bootbox.alert(r.data("description"))
            })) : r.css("display", ""), r.data("description", n)) : r.hide()
        }))
    }, {icon: "glyphicon glyphicon-info-sign", mode: "popover"}), c.extend({
        getFilterDescription: function (e, t) {
            return e ? "function" == typeof e.description ? e.description.call(this, t) : e.description : void 0
        }
    }), c.define("invert", function (r) {
        var n = this, i = c.selectors;
        this.on("afterInit", function () {
            n.$el.on("click.queryBuilder", "[data-invert=group]", function () {
                var e = $(this).closest(i.group_container);
                n.invert(n.getModel(e), r)
            }), r.display_rules_button && r.invert_rules && n.$el.on("click.queryBuilder", "[data-invert=rule]", function () {
                var e = $(this).closest(i.rule_container);
                n.invert(n.getModel(e), r)
            })
        }), r.disable_template || (this.on("getGroupTemplate.filter", function (e) {
            var t = $(e.value);
            t.find(i.condition_container).after('<button type="button" class="btn btn-xs btn-default" data-invert="group"><i class="' + r.icon + '"></i> ' + n.translate("invert") + "</button>"), e.value = t.prop("outerHTML")
        }), r.display_rules_button && r.invert_rules && this.on("getRuleTemplate.filter", function (e) {
            var t = $(e.value);
            t.find(i.rule_actions).prepend('<button type="button" class="btn btn-xs btn-default" data-invert="rule"><i class="' + r.icon + '"></i> ' + n.translate("invert") + "</button>"), e.value = t.prop("outerHTML")
        }))
    }, {
        icon: "glyphicon glyphicon-random",
        recursive: !0,
        invert_rules: !0,
        display_rules_button: !1,
        silent_fail: !1,
        disable_template: !1
    }), c.defaults({
        operatorOpposites: {
            equal: "not_equal",
            not_equal: "equal",
            in: "not_in",
            not_in: "in",
            less: "greater_or_equal",
            less_or_equal: "greater",
            greater: "less_or_equal",
            greater_or_equal: "less",
            between: "not_between",
            not_between: "between",
            begins_with: "not_begins_with",
            not_begins_with: "begins_with",
            contains: "not_contains",
            not_contains: "contains",
            ends_with: "not_ends_with",
            not_ends_with: "ends_with",
            is_empty: "is_not_empty",
            is_not_empty: "is_empty",
            is_null: "is_not_null",
            is_not_null: "is_null"
        }, conditionOpposites: {AND: "OR", OR: "AND"}
    }), c.extend({
        invert: function (e, t) {
            if (!(e instanceof i)) {
                if (!this.model.root) return;
                t = e, e = this.model.root
            }
            if ("object" != typeof t && (t = {}), void 0 === t.recursive && (t.recursive = !0), void 0 === t.invert_rules && (t.invert_rules = !0), void 0 === t.silent_fail && (t.silent_fail = !1), void 0 === t.trigger && (t.trigger = !0), e instanceof a) {
                if (this.settings.conditionOpposites[e.condition] ? e.condition = this.settings.conditionOpposites[e.condition] : t.silent_fail || h.error("InvertCondition", 'Unknown inverse of condition "{0}"', e.condition), t.recursive) {
                    var r = $.extend({}, t, {trigger: !1});
                    e.each(function (e) {
                        t.invert_rules && this.invert(e, r)
                    }, function (e) {
                        this.invert(e, r)
                    }, this)
                }
            } else if (e instanceof l && e.operator && !e.filter.no_invert) if (this.settings.operatorOpposites[e.operator.type]) {
                var n = this.settings.operatorOpposites[e.operator.type];
                e.filter.operators && -1 == e.filter.operators.indexOf(n) || (e.operator = this.getOperatorByType(n))
            } else t.silent_fail || h.error("InvertOperator", 'Unknown inverse of operator "{0}"', e.operator.type);
            t.trigger && (this.trigger("afterInvert", e, t), this.trigger("rulesChanged"))
        }
    }), c.defaults({
        mongoOperators: {
            equal: function (e) {
                return e[0]
            }, not_equal: function (e) {
                return {$ne: e[0]}
            }, in: function (e) {
                return {$in: e}
            }, not_in: function (e) {
                return {$nin: e}
            }, less: function (e) {
                return {$lt: e[0]}
            }, less_or_equal: function (e) {
                return {$lte: e[0]}
            }, greater: function (e) {
                return {$gt: e[0]}
            }, greater_or_equal: function (e) {
                return {$gte: e[0]}
            }, between: function (e) {
                return {$gte: e[0], $lte: e[1]}
            }, not_between: function (e) {
                return {$lt: e[0], $gt: e[1]}
            }, begins_with: function (e) {
                return {$regex: "^" + h.escapeRegExp(e[0])}
            }, not_begins_with: function (e) {
                return {$regex: "^(?!" + h.escapeRegExp(e[0]) + ")"}
            }, contains: function (e) {
                return {$regex: h.escapeRegExp(e[0])}
            }, not_contains: function (e) {
                return {$regex: "^((?!" + h.escapeRegExp(e[0]) + ").)*$", $options: "s"}
            }, ends_with: function (e) {
                return {$regex: h.escapeRegExp(e[0]) + "$"}
            }, not_ends_with: function (e) {
                return {$regex: "(?<!" + h.escapeRegExp(e[0]) + ")$"}
            }, is_empty: function (e) {
                return ""
            }, is_not_empty: function (e) {
                return {$ne: ""}
            }, is_null: function (e) {
                return null
            }, is_not_null: function (e) {
                return {$ne: null}
            }
        }, mongoRuleOperators: {
            $eq: function (e) {
                return {val: e, op: null === e ? "is_null" : "" === e ? "is_empty" : "equal"}
            }, $ne: function (e) {
                return {val: e = e.$ne, op: null === e ? "is_not_null" : "" === e ? "is_not_empty" : "not_equal"}
            }, $regex: function (e) {
                return "^(?!" == (e = e.$regex).slice(0, 4) && ")" == e.slice(-1) ? {
                    val: e.slice(4, -1),
                    op: "not_begins_with"
                } : "^((?!" == e.slice(0, 5) && ").)*$" == e.slice(-5) ? {
                    val: e.slice(5, -5),
                    op: "not_contains"
                } : "(?<!" == e.slice(0, 4) && ")$" == e.slice(-2) ? {
                    val: e.slice(4, -2),
                    op: "not_ends_with"
                } : "$" == e.slice(-1) ? {
                    val: e.slice(0, -1),
                    op: "ends_with"
                } : "^" == e.slice(0, 1) ? {val: e.slice(1), op: "begins_with"} : {val: e, op: "contains"}
            }, between: function (e) {
                return {val: [e.$gte, e.$lte], op: "between"}
            }, not_between: function (e) {
                return {val: [e.$lt, e.$gt], op: "not_between"}
            }, $in: function (e) {
                return {val: e.$in, op: "in"}
            }, $nin: function (e) {
                return {val: e.$nin, op: "not_in"}
            }, $lt: function (e) {
                return {val: e.$lt, op: "less"}
            }, $lte: function (e) {
                return {val: e.$lte, op: "less_or_equal"}
            }, $gt: function (e) {
                return {val: e.$gt, op: "greater"}
            }, $gte: function (e) {
                return {val: e.$gte, op: "greater_or_equal"}
            }
        }
    }), c.extend({
        getMongo: function (e) {
            if (!(e = void 0 === e ? this.getRules() : e)) return null;
            var l = this;
            return function i(e) {
                if (e.condition || (e.condition = l.settings.default_condition), -1 === ["AND", "OR"].indexOf(e.condition.toUpperCase()) && h.error("UndefinedMongoCondition", 'Unable to build MongoDB query with condition "{0}"', e.condition), !e.rules) return {};
                var o = [];
                e.rules.forEach(function (e) {
                    if (e.rules && 0 < e.rules.length) o.push(i(e)); else {
                        var t = l.settings.mongoOperators[e.operator], r = l.getOperatorByType(e.operator);
                        void 0 === t && h.error("UndefinedMongoOperator", 'Unknown MongoDB operation for operator "{0}"', e.operator), 0 !== r.nb_inputs && (e.value instanceof Array || (e.value = [e.value]));
                        var n = {};
                        n[l.change("getMongoDBField", e.field, e)] = t.call(l, e.value), o.push(l.change("ruleToMongo", n, e, e.value, t))
                    }
                });
                var t = {};
                return t["$" + e.condition.toLowerCase()] = o, l.change("groupToMongo", t, e)
            }(e)
        }, getRulesFromMongo: function (e) {
            if (null == e) return null;
            var d = this;
            if ("rules" in (e = d.change("parseMongoNode", e)) && "condition" in e) return e;
            if ("id" in e && "operator" in e && "value" in e) return {
                condition: this.settings.default_condition,
                rules: [e]
            };
            var t = d.getMongoCondition(e);
            return t || h.error("MongoParse", "Invalid MongoDB query format"), function u(e, t) {
                var r = e[t], p = [];
                return r.forEach(function (e) {
                    if ("rules" in (e = d.change("parseMongoNode", e)) && "condition" in e) p.push(e); else if ("id" in e && "operator" in e && "value" in e) p.push(e); else {
                        var t = d.getMongoCondition(e);
                        if (t) p.push(u(e, t)); else {
                            var r = Object.keys(e)[0], n = e[r], i = d.getMongoOperator(n);
                            void 0 === i && h.error("MongoParse", "Invalid MongoDB query format");
                            var o = d.settings.mongoRuleOperators[i];
                            void 0 === o && h.error("UndefinedMongoOperator", 'JSON Rule operation unknown for operator "{0}"', i);
                            var l = o.call(d, n), s = d.getMongoDBFieldID(r, n),
                                a = d.change("mongoToRule", {id: s, field: r, operator: l.op, value: l.val}, e);
                            p.push(a)
                        }
                    }
                }), d.change("mongoToGroup", {condition: t.replace("$", "").toUpperCase(), rules: p}, e)
            }(e, t)
        }, setRulesFromMongo: function (e) {
            this.setRules(this.getRulesFromMongo(e))
        }, getMongoDBFieldID: function (t, e) {
            var r = this.filters.filter(function (e) {
                return e.field === t
            });
            return 1 === r.length ? r[0].id : this.change("getMongoDBFieldID", t, e)
        }, getMongoOperator: function (e) {
            if (null === e || "object" != typeof e) return "$eq";
            if (void 0 !== e.$gte && void 0 !== e.$lte) return "between";
            if (void 0 !== e.$lt && void 0 !== e.$gt) return "not_between";
            var t = Object.keys(e).filter(function (e) {
                return !!this.settings.mongoRuleOperators[e]
            }.bind(this));
            return 1 === t.length ? t[0] : void 0
        }, getMongoCondition: function (e) {
            for (var t = Object.keys(e), r = 0, n = t.length; r < n; r++) if ("$or" === t[r].toLowerCase() || "$and" === t[r].toLowerCase()) return t[r]
        }
    }), c.define("not-group", function (r) {
        var n = this;
        this.on("afterInit", function () {
            n.$el.on("click.queryBuilder", "[data-not=group]", function () {
                var e = $(this).closest(c.selectors.group_container), t = n.getModel(e);
                t.not = !t.not
            }), n.model.on("update", function (e, t, r) {
                t instanceof a && "not" === r && n.updateGroupNot(t)
            })
        }), this.on("afterAddGroup", function (e, t) {
            t.__.not = !1
        }), r.disable_template || this.on("getGroupTemplate.filter", function (e) {
            var t = $(e.value);
            t.find(c.selectors.condition_container).prepend('<button type="button" class="btn btn-xs btn-default" data-not="group"><i class="' + r.icon_unchecked + '"></i> ' + n.translate("NOT") + "</button>"), e.value = t.prop("outerHTML")
        }), this.on("groupToJson.filter", function (e, t) {
            e.value.not = t.not
        }), this.on("jsonToGroup.filter", function (e, t) {
            e.value.not = !!t.not
        }), this.on("groupToSQL.filter", function (e, t) {
            t.not && (e.value = "NOT ( " + e.value + " )")
        }), this.on("parseSQLNode.filter", function (e) {
            e.value.name && "NOT" == e.value.name.toUpperCase() && (e.value = e.value.arguments.value[0], -1 === ["AND", "OR"].indexOf(e.value.operation.toUpperCase()) && (e.value = new SQLParser.nodes.Op(n.settings.default_condition, e.value, null)), e.value.not = !0)
        }), this.on("sqlGroupsDistinct.filter", function (e, t, r, n) {
            r.not && 0 < n && (e.value = !0)
        }), this.on("sqlToGroup.filter", function (e, t) {
            e.value.not = !!t.not
        }), this.on("groupToMongo.filter", function (e, t) {
            var r = "$" + t.condition.toLowerCase();
            t.not && e.value[r] && (e.value = {$nor: [e.value]})
        }), this.on("parseMongoNode.filter", function (e) {
            var t = Object.keys(e.value);
            "$nor" == t[0] && (e.value = e.value[t[0]][0], e.value.not = !0)
        }), this.on("mongoToGroup.filter", function (e, t) {
            e.value.not = !!t.not
        })
    }, {
        icon_unchecked: "glyphicon glyphicon-unchecked",
        icon_checked: "glyphicon glyphicon-check",
        disable_template: !1
    }), h.defineModelProperties(a, ["not"]), c.selectors.group_not = c.selectors.group_header + " [data-not=group]", c.extend({
        updateGroupNot: function (e) {
            var t = this.plugins["not-group"];
            e.$el.find(">" + c.selectors.group_not).toggleClass("active", e.not).find("i").attr("class", e.not ? t.icon_checked : t.icon_unchecked), this.trigger("afterUpdateGroupNot", e), this.trigger("rulesChanged")
        }
    }), c.define("sortable", function (n) {
        var i, o, l, s;
        "interact" in window || h.error("MissingLibrary", 'interact.js is required to use "sortable" plugin. Get it here: http://interactjs.io'), void 0 !== n.default_no_sortable && (h.error(!1, "Config", 'Sortable plugin : "default_no_sortable" options is deprecated, use standard "default_rule_flags" and "default_group_flags" instead'), this.settings.default_rule_flags.no_sortable = this.settings.default_group_flags.no_sortable = n.default_no_sortable), interact.dynamicDrop(!0), interact.pointerMoveTolerance(10), this.on("afterAddRule afterAddGroup", function (e, t) {
            if (t != i) {
                var r = e.builder;
                n.inherit_no_sortable && t.parent && t.parent.flags.no_sortable && (t.flags.no_sortable = !0), n.inherit_no_drop && t.parent && t.parent.flags.no_drop && (t.flags.no_drop = !0), t.flags.no_sortable || interact(t.$el[0]).draggable({
                    allowFrom: c.selectors.drag_handle,
                    onstart: function (e) {
                        s = !1, l = r.getModel(e.target), o = l.$el.clone().appendTo(l.$el.parent()).width(l.$el.outerWidth()).addClass("dragging");
                        var t = $('<div class="rule-placeholder">&nbsp;</div>').height(l.$el.outerHeight());
                        i = l.parent.addRule(t, l.getPos()), l.$el.hide()
                    },
                    onmove: function (e) {
                        o[0].style.top = e.clientY - 15 + "px", o[0].style.left = e.clientX - 15 + "px"
                    },
                    onend: function (e) {
                        e.dropzone && (u(l, $(e.relatedTarget), r), s = !0), o.remove(), o = void 0, i.drop(), i = void 0, l.$el.css("display", ""), r.trigger("afterMove", l), r.trigger("rulesChanged")
                    }
                }), t.flags.no_drop || (interact(t.$el[0]).dropzone({
                    accept: c.selectors.rule_and_group_containers,
                    ondragenter: function (e) {
                        u(i, $(e.target), r)
                    },
                    ondrop: function (e) {
                        s || u(l, $(e.target), r)
                    }
                }), t instanceof a && interact(t.$el.find(c.selectors.group_header)[0]).dropzone({
                    accept: c.selectors.rule_and_group_containers,
                    ondragenter: function (e) {
                        u(i, $(e.target), r)
                    },
                    ondrop: function (e) {
                        s || u(l, $(e.target), r)
                    }
                }))
            }
        }), this.on("beforeDeleteRule beforeDeleteGroup", function (e, t) {
            e.isDefaultPrevented() || (interact(t.$el[0]).unset(), t instanceof a && interact(t.$el.find(c.selectors.group_header)[0]).unset())
        }), this.on("afterApplyRuleFlags afterApplyGroupFlags", function (e, t) {
            t.flags.no_sortable && t.$el.find(".drag-handle").remove()
        }), n.disable_template || (this.on("getGroupTemplate.filter", function (e, t) {
            if (1 < t) {
                var r = $(e.value);
                r.find(c.selectors.condition_container).after('<div class="drag-handle"><i class="' + n.icon + '"></i></div>'), e.value = r.prop("outerHTML")
            }
        }), this.on("getRuleTemplate.filter", function (e) {
            var t = $(e.value);
            t.find(c.selectors.rule_header).after('<div class="drag-handle"><i class="' + n.icon + '"></i></div>'), e.value = t.prop("outerHTML")
        }))
    }, {
        inherit_no_sortable: !0,
        inherit_no_drop: !0,
        icon: "glyphicon glyphicon-sort",
        disable_template: !1
    }), c.selectors.rule_and_group_containers = c.selectors.rule_container + ", " + c.selectors.group_container, c.selectors.drag_handle = ".drag-handle", c.defaults({
        default_rule_flags: {
            no_sortable: !1,
            no_drop: !1
        }, default_group_flags: {no_sortable: !1, no_drop: !1}
    }), c.define("sql-support", function (e) {
    }, {boolean_as_integer: !0}), c.defaults({
        sqlOperators: {
            equal: {op: "= ?"},
            not_equal: {op: "!= ?"},
            in: {op: "IN (?)", sep: ", "},
            not_in: {op: "NOT IN (?)", sep: ", "},
            less: {op: "< ?"},
            less_or_equal: {op: "<= ?"},
            greater: {op: "> ?"},
            greater_or_equal: {op: ">= ?"},
            between: {op: "BETWEEN ?", sep: " AND "},
            not_between: {op: "NOT BETWEEN ?", sep: " AND "},
            begins_with: {op: "LIKE ?", mod: "{0}%"},
            not_begins_with: {op: "NOT LIKE ?", mod: "{0}%"},
            contains: {op: "LIKE ?", mod: "%{0}%"},
            not_contains: {op: "NOT LIKE ?", mod: "%{0}%"},
            ends_with: {op: "LIKE ?", mod: "%{0}"},
            not_ends_with: {op: "NOT LIKE ?", mod: "%{0}"},
            is_empty: {op: "= ''"},
            is_not_empty: {op: "!= ''"},
            is_null: {op: "IS NULL"},
            is_not_null: {op: "IS NOT NULL"}
        }, sqlRuleOperator: {
            "=": function (e) {
                return {val: e, op: "" === e ? "is_empty" : "equal"}
            }, "!=": function (e) {
                return {val: e, op: "" === e ? "is_not_empty" : "not_equal"}
            }, LIKE: function (e) {
                return "%" == e.slice(0, 1) && "%" == e.slice(-1) ? {
                    val: e.slice(1, -1),
                    op: "contains"
                } : "%" == e.slice(0, 1) ? {
                    val: e.slice(1),
                    op: "ends_with"
                } : "%" == e.slice(-1) ? {
                    val: e.slice(0, -1),
                    op: "begins_with"
                } : void h.error("SQLParse", 'Invalid value for LIKE operator "{0}"', e)
            }, "NOT LIKE": function (e) {
                return "%" == e.slice(0, 1) && "%" == e.slice(-1) ? {
                    val: e.slice(1, -1),
                    op: "not_contains"
                } : "%" == e.slice(0, 1) ? {
                    val: e.slice(1),
                    op: "not_ends_with"
                } : "%" == e.slice(-1) ? {
                    val: e.slice(0, -1),
                    op: "not_begins_with"
                } : void h.error("SQLParse", 'Invalid value for NOT LIKE operator "{0}"', e)
            }, IN: function (e) {
                return {val: e, op: "in"}
            }, "NOT IN": function (e) {
                return {val: e, op: "not_in"}
            }, "<": function (e) {
                return {val: e, op: "less"}
            }, "<=": function (e) {
                return {val: e, op: "less_or_equal"}
            }, ">": function (e) {
                return {val: e, op: "greater"}
            }, ">=": function (e) {
                return {val: e, op: "greater_or_equal"}
            }, BETWEEN: function (e) {
                return {val: e, op: "between"}
            }, "NOT BETWEEN": function (e) {
                return {val: e, op: "not_between"}
            }, IS: function (e) {
                return null !== e && h.error("SQLParse", "Invalid value for IS operator"), {val: null, op: "is_null"}
            }, "IS NOT": function (e) {
                return null !== e && h.error("SQLParse", "Invalid value for IS operator"), {
                    val: null,
                    op: "is_not_null"
                }
            }
        }, sqlStatements: {
            question_mark: function () {
                var r = [];
                return {
                    add: function (e, t) {
                        return r.push(t), "?"
                    }, run: function () {
                        return r
                    }
                }
            }, numbered: function (r) {
                (!r || 1 < r.length) && (r = "$");
                var n = 0, i = [];
                return {
                    add: function (e, t) {
                        return i.push(t), r + ++n
                    }, run: function () {
                        return i
                    }
                }
            }, named: function (n) {
                (!n || 1 < n.length) && (n = ":");
                var i = {}, o = {};
                return {
                    add: function (e, t) {
                        i[e.field] || (i[e.field] = 1);
                        var r = e.field + "_" + i[e.field]++;
                        return o[r] = t, n + r
                    }, run: function () {
                        return o
                    }
                }
            }
        }, sqlRuleStatement: {
            question_mark: function (t) {
                var r = 0;
                return {
                    parse: function (e) {
                        return "?" == e ? t[r++] : e
                    }, esc: function (e) {
                        return e.replace(/\?/g, "'?'")
                    }
                }
            }, numbered: function (t, r) {
                (!r || 1 < r.length) && (r = "$");
                var n = new RegExp("^\\" + r + "[0-9]+$"), i = new RegExp("\\" + r + "([0-9]+)", "g");
                return {
                    parse: function (e) {
                        return n.test(e) ? t[e.slice(1) - 1] : e
                    }, esc: function (e) {
                        return e.replace(i, "'" + ("$" == r ? "$$" : r) + "$1'")
                    }
                }
            }, named: function (t, r) {
                (!r || 1 < r.length) && (r = ":");
                var n = new RegExp("^\\" + r), i = new RegExp("\\" + r + "(" + Object.keys(t).join("|") + ")", "g");
                return {
                    parse: function (e) {
                        return n.test(e) ? t[e.slice(1)] : e
                    }, esc: function (e) {
                        return e.replace(i, "'" + ("$" == r ? "$$" : r) + "$1'")
                    }
                }
            }
        }
    }), c.extend({
        getSQL: function (a, u, e) {
            if (!(e = void 0 === e ? this.getRules() : e)) return null;
            u = u ? "\n" : " ";
            var p = this.getPluginOptions("sql-support", "boolean_as_integer");
            if (!0 === a && (a = "question_mark"), "string" == typeof a) {
                var t = o(a);
                a = this.settings.sqlStatements[t[1]](t[2])
            }
            var d = this, r = function l(e) {
                if (e.condition || (e.condition = d.settings.default_condition), -1 === ["AND", "OR"].indexOf(e.condition.toUpperCase()) && h.error("UndefinedSQLCondition", 'Unable to build SQL query with condition "{0}"', e.condition), !e.rules) return "";
                var s = [];
                e.rules.forEach(function (r) {
                    if (r.rules && 0 < r.rules.length) s.push("(" + u + l(r) + u + ")" + u); else {
                        var n = d.settings.sqlOperators[r.operator], e = d.getOperatorByType(r.operator), i = "";
                        void 0 === n && h.error("UndefinedSQLOperator", 'Unknown SQL operation for operator "{0}"', r.operator), 0 !== e.nb_inputs && (r.value instanceof Array || (r.value = [r.value]), r.value.forEach(function (e, t) {
                            0 < t && (i += n.sep), "boolean" == r.type && p ? e = e ? 1 : 0 : a || "integer" === r.type || "double" === r.type || "boolean" === r.type || (e = h.escapeString(e)), n.mod && (e = h.fmt(n.mod, e)), a ? i += a.add(r, e) : ("string" == typeof e && (e = "'" + e + "'"), i += e)
                        }));
                        var t = function (e) {
                            return n.op.replace("?", function () {
                                return e
                            })
                        }, o = d.change("getSQLField", r.field, r) + " " + t(i);
                        s.push(d.change("ruleToSQL", o, r, i, t))
                    }
                });
                var t = s.join(" " + e.condition + u);
                return d.change("groupToSQL", t, e)
            }(e);
            return a ? {sql: r, params: a.run()} : {sql: r}
        }, getRulesFromSQL: function (e, c) {
            "SQLParser" in window || h.error("MissingLibrary", "SQLParser is required to parse SQL queries. Get it here https://github.com/mistic100/sql-parser");
            var f = this;
            if ("string" == typeof e && (e = {sql: e}), !0 === c && (c = "question_mark"), "string" == typeof c) {
                var t = o(c);
                c = this.settings.sqlRuleStatement[t[1]](e.params, t[2])
            }
            c && (e.sql = c.esc(e.sql)), 0 !== e.sql.toUpperCase().indexOf("SELECT") && (e.sql = "SELECT * FROM table WHERE " + e.sql);
            var r = SQLParser.parse(e.sql);
            r.where || h.error("SQLParse", "No WHERE clause found");
            var n = f.change("parseSQLNode", r.where.conditions);
            if ("rules" in n && "condition" in n) return n;
            if ("id" in n && "operator" in n && "value" in n) return {
                condition: this.settings.default_condition,
                rules: [n]
            };
            var i = f.change("sqlToGroup", {condition: this.settings.default_condition, rules: []}, n), g = i;
            return function e(t, r) {
                if (null !== t) if ("rules" in (t = f.change("parseSQLNode", t)) && "condition" in t) g.rules.push(t); else if ("id" in t && "operator" in t && "value" in t) g.rules.push(t); else if ("left" in t && "right" in t && "operation" in t || h.error("SQLParse", "Unable to parse WHERE clause"), -1 !== ["AND", "OR"].indexOf(t.operation.toUpperCase())) {
                    if (f.change("sqlGroupsDistinct", 0 < r && g.condition != t.operation.toUpperCase(), g, t, r)) {
                        var n = f.change("sqlToGroup", {condition: f.settings.default_condition, rules: []}, t);
                        g.rules.push(n), g = n
                    }
                    g.condition = t.operation.toUpperCase(), r++;
                    var i = g;
                    e(t.left, r), g = i, e(t.right, r)
                } else {
                    var o;
                    $.isPlainObject(t.right.value) && h.error("SQLParse", "Value format not supported for {0}.", t.left.value), o = $.isArray(t.right.value) ? t.right.value.map(function (e) {
                        return e.value
                    }) : t.right.value, c && (o = $.isArray(o) ? o.map(c.parse) : c.parse(o));
                    var l = t.operation.toUpperCase();
                    "<>" == l && (l = "!=");
                    var s = f.settings.sqlRuleOperator[l];
                    void 0 === s && h.error("UndefinedSQLOperator", 'Invalid SQL operation "{0}".', t.operation);
                    var a, u = s.call(this, o, t.operation);
                    "values" in t.left ? a = t.left.values.join(".") : "value" in t.left ? a = t.left.value : h.error("SQLParse", "Cannot find field name in {0}", JSON.stringify(t.left));
                    var p = f.getSQLFieldID(a, o),
                        d = f.change("sqlToRule", {id: p, field: a, operator: u.op, value: u.val}, t);
                    g.rules.push(d)
                }
            }(n, 0), i
        }, setRulesFromSQL: function (e, t) {
            this.setRules(this.getRulesFromSQL(e, t))
        }, getSQLFieldID: function (t, e) {
            var r = this.filters.filter(function (e) {
                return e.field.toLowerCase() === t.toLowerCase()
            });
            return 1 === r.length ? r[0].id : this.change("getSQLFieldID", t, e)
        }
    }), c.define("unique-filter", function () {
        this.status.used_filters = {}, this.on("afterUpdateRuleFilter", this.updateDisabledFilters), this.on("afterDeleteRule", this.updateDisabledFilters), this.on("afterCreateRuleFilters", this.applyDisabledFilters), this.on("afterReset", this.clearDisabledFilters), this.on("afterClear", this.clearDisabledFilters), this.on("getDefaultFilter.filter", function (t, r) {
            var n = t.builder;
            (n.updateDisabledFilters(), t.value.id in n.status.used_filters) && (n.filters.some(function (e) {
                if (!(e.id in n.status.used_filters) || 0 < n.status.used_filters[e.id].length && -1 === n.status.used_filters[e.id].indexOf(r.parent)) return t.value = e, !0
            }) || (h.error(!1, "UniqueFilter", "No more non-unique filters available"), t.value = void 0))
        })
    }), c.extend({
        updateDisabledFilters: function (e) {
            var r = e ? e.builder : this;
            r.status.used_filters = {}, r.model && (!function t(e) {
                e.each(function (e) {
                    e.filter && e.filter.unique && (r.status.used_filters[e.filter.id] || (r.status.used_filters[e.filter.id] = []), "group" == e.filter.unique && r.status.used_filters[e.filter.id].push(e.parent))
                }, function (e) {
                    t(e)
                })
            }(r.model.root), r.applyDisabledFilters(e))
        }, clearDisabledFilters: function (e) {
            var t = e ? e.builder : this;
            t.status.used_filters = {}, t.applyDisabledFilters(e)
        }, applyDisabledFilters: function (e) {
            var r = e ? e.builder : this;
            r.$el.find(c.selectors.filter_container + " option").prop("disabled", !1), $.each(r.status.used_filters, function (t, e) {
                0 === e.length ? r.$el.find(c.selectors.filter_container + ' option[value="' + t + '"]:not(:selected)').prop("disabled", !0) : e.forEach(function (e) {
                    e.each(function (e) {
                        e.$el.find(c.selectors.filter_container + ' option[value="' + t + '"]:not(:selected)').prop("disabled", !0)
                    })
                })
            }), r.settings.plugins && r.settings.plugins["bt-selectpicker"] && r.$el.find(c.selectors.rule_filter).selectpicker("render")
        }
    }), c.regional.en = {
        __locale: "Français (fr)",
        __author: 'Chikhaoui Mohamed Ali',
        add_rule: "Ajouter une règle",
        add_group: "Ajouter un groupe",
        delete_rule: "Supprimer",
        delete_group: "Supprimer",
        conditions: {AND: "ET", OR: "OU"},
        operators: {
            equal: "égale",
            not_equal: "différent",
            in: "dans",
            not_in: "pas dedans",
            less: "inférieur",
            less_or_equal: "inférieur ou égal",
            greater: "supérieur",
            greater_or_equal: "supérieur ou égal",
            between: "entre",
            not_between: "pas entre",
            begins_with: "commence par",
            not_begins_with: "ne commence pas par",
            contains: "contient",
            not_contains: "ne contient pas",
            ends_with: "se termine par",
            not_ends_with: "ne se termine pas par",
            is_empty: "est vide",
            is_not_empty: "n'est pas vide",
            is_null: "est nulle",
            is_not_null: "est non nulle"
        },
        errors: {
            no_filter: "Aucun filtre sélectionné",
            empty_group: "Le groupe est vide",
            radio_empty: "Aucune valeur sélectionnée",
            checkbox_empty: "Aucune valeur sélectionnée",
            select_empty: "Aucune valeur sélectionnée",
            string_empty: "Valeur vide",
            string_exceed_min_length: "Doit contenir au moins {0} caractères",
            string_exceed_max_length: "Ne doit pas contenir plus de {0} caractères",
            string_invalid_format: "Format non valide ({0})",
            number_nan: "Pas un nombre",
            number_not_integer: "Pas un entier",
            number_not_double: "Pas un vrai nombre",
            number_exceed_min: "Doit être supérieur à {0}",
            number_exceed_max: "Doit être inférieur à {0}",
            number_wrong_step: "Doit être un multiple de {0}",
            number_between_invalid: "Valeurs non valides, {0} est supérieur à {1}",
            datetime_empty: "Valeur vide",
            datetime_invalid: "Format de date non valide ({0})",
            datetime_exceed_min: "Doit être après {0}",
            datetime_exceed_max: "Doit être avant {0}",
            datetime_between_invalid: "Valeurs non valides, {0} est supérieur à {1}",
            boolean_not_valid: "Pas un booléen",
            operator_not_multiple: 'L\'opérateur "{1}" ne peut pas accepter plusieurs valeurs'
        },
        invert: "Inverser",
        NOT: "Ne pas"
    }, c.defaults({lang_code: "en"}), c
});