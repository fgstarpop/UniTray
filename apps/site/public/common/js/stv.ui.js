function g(i) {
    return document.getElementById(i);
  }
  function q(i) {
    return document.querySelectorAll(i);
  }
  Element.prototype.qq = function (i) {
    return this.querySelectorAll(i);
  };
  Element.prototype.gg = function (i) {
    return this.querySelector(i);
  };
  function titleCase(str) {
    var splitStr = str.toLowerCase().split(' ');
    for (var i = 0; i < splitStr.length; i++) {
      splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
    }
    return splitStr.join(' ');
  }
  String.prototype.contain = function (str) {
    return this.indexOf(str) > - 1;
  }
  String.prototype.endwith = function (suffix) {
    return this.indexOf(suffix, this.length - suffix.length) !== - 1;
  }
  Array.prototype.contain = function (obj) {
    return this.indexOf(obj) >= 0;
  }
  String.prototype.lastChar = function () {
    return this[this.length - 1];
  }
  function getSelectionText() {
    var text = '';
    if (window.getSelection) {
      text = window.getSelection().toString();
    } else if (document.selection && document.selection.type != 'Control') {
      text = document.selection.createRange().text;
    }
    return text;
  }
  function cap(s) {
    if (typeof (s) != 'undefined')
    return s.charAt(0).toUpperCase() + s.slice(1);
  }
  function clearSelection() {
    if (document.selection)
    document.selection.empty();
     else if (window.getSelection)
    window.getSelection().collapseToStart();
  }
  function createModal(name) {
    var div = document.createElement('div');
    div.innerHTML = '<div class="modal fade" style="font-size:12px;">' +
    '<div class="modal-dialog modal-md modal-dialog-centered" onclick="event.stopPropagation()">' +
    '\t<div class="modal-content">' +
    '<div class="modal-header">' +
    '<h6 class="modal-title"></h6>' +
    '<button type="button" class="close" >&times;</button>' +
    '</div>' +
    '<div class="modal-body">' +
    '</div>' +
    '<div class="modal-footer">' +
    '' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>';
    div.body = function () {
      return this.querySelector('.modal-body');
    }
    div.tit = function (str) {
      this.querySelector('.modal-title').innerHTML = str;
    }
    div.querySelector('.modal-title').innerHTML = name;
    div.footer = function () {
      return this.querySelector('.modal-footer');
    }
    div.button = function (text, click, colorclass, datatag) {
      var btn = document.createElement('button');
      btn.innerHTML = text;
      btn.setAttribute('onclick', click);
      btn.setAttribute('class', 'btn ' + colorclass);
      if (typeof datatag != 'undefined') {
        btn.setAttribute('data-tag', datatag);
        btn.data = function (a) {
          if (typeof a == 'undefined')
          return this.getAttribute('data-tag');
           else {
            this.setAttribute('data-tag', a);
          }
        }
      }
      this.footer().appendChild(btn);
      return btn;
    }
    div.show = function () {
      document.body.appendChild(this);
      $(this.children[0]).modal('show');
    }
    div.size = function (si) {
      this.querySelector('.modal-dialog').className = 'modal-dialog modal-dialog-centered modal-' + si;
    }
    div.onhide = function () {
    }
    div.hide = function () {
      $(this.children[0]).modal('hide');
      setTimeout(function () {
        div.innerHTML = '';
        div = null;
      }, 500);
      this.onhide();
    }
    div.querySelector('.fade').addEventListener('click', function () {
      div.hide();
    });
    div.querySelector('.close').addEventListener('click', function () {
      div.hide();
    });
    return div;
  }
  function ajax(params, callb) {
    var http = new XMLHttpRequest();
    var url = '/editname';

    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
      if (http.readyState == 4 && http.status == 200) {
        if (callb != null) callb(this.responseText);
      } else if (http.readyState == 4 && http.status == 502) {
        setTimeout(function () {
          ajax(params, callb);
        }, 1000);
      }
    }
    http.send(params);
  }
  var ui = {
  }
  ui.global = {
  }
  function randonInt(min, max) {
    return Math.floor(Math.random() * (max - min)) + min;
  }
  ui.createName = function () {
    var a = 0;
    while (a in ui.global) {
      a = randonInt(0, 99999);
    }
    return a;
  }
  ui.alert = function (text, title) {
    var mdname = this.createName();
    this.global[mdname] = createModal(title || 'Thông báo');
    this.global[mdname].body().innerHTML = text;
    this.global[mdname].button('Biết rồi!', 'ui.global[' + mdname + '].hide()', 'btn-primary');
    this.global[mdname].onhide = function () {
      delete ui.global[mdname];
    }
    this.global[mdname].show();
  }
  ui.notif = function (text) {
    var notifname = this.createName();
    var html = '<div style="position:fixed;right:10px;top:10px;border-radius:8px;width:160px;min-height:60px;color:black;background:#e9e9e9;padding:8px;opacity:0">'
    + '\tThông báo<br>'
    + text
    + '</div>';
    this.global[notifname] = document.createElement('div');
    this.global[notifname].innerHTML = html;
    document.body.appendChild(this.global[notifname]);
    this.global[notifname].children[0].style.opacity = 0;
    this.global[notifname].children[0].style.transition = 'opacity 0.6s'
    this.global[notifname].children[0].offsetHeight
    this.global[notifname].children[0].style.opacity = 1;
    setTimeout(function () {
      ui.global[notifname].children[0].style.opacity = 0;
      setTimeout(function () {
        document.body.removeChild(ui.global[notifname]);
        delete ui.global[notifname];
      }, 500);
    }, 3000);
  }
  function getPosFromEvent(e) {
    if (e.type == 'touchstart' || e.type == 'touchmove' || e.type == 'touchend' || e.type == 'touchcancel') {
      var touch = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0];
      return {
        x: touch.pageX,
        y: touch.pageY
      };
    } else if (e.type == 'mousedown' || e.type == 'mouseup' || e.type == 'mousemove' || e.type == 'mouseover' || e.type == 'mouseout' || e.type == 'mouseenter' || e.type == 'mouseleave') {
      return {
        x: e.clientX,
        y: e.clientY
      };
    }
  }
  ui.longpress = function (node, calb) {
    var holder = {
      threshold: 500,
      isdown: false,
      holdend: calb,
      startx: 0,
      starty: 0,
      cx: 0,
      cy: 0,
      node: node,
      holdstart: function () {
        var pos = getPosFromEvent(event);
        this.startx = pos.x;
        this.starty = pos.y;
        this.node.addEventListener('touchmove', this.move);
        this.node.addEventListener('mousemove', this.move);
        this.node.addEventListener('touchend', this.end);
        this.node.addEventListener('mouseup', this.end);
        this.timer = setTimeout(function () {
          if (holder.isdown)
          if (Math.abs(holder.cx - holder.startx) < 20 && Math.abs(holder.cy - holder.starty) < 20) {
            holder.holdend(holder.node);
          }
        }, this.threshold);
        this.isdown = true;
      },
      move: function () {
        var pos = getPosFromEvent(event);
        holder.cx = pos.x;
        holder.cy = pos.y;
      },
      end: function () {
        holder.isdown = false;
        this.removeEventListener('touchmove', holder.move);
        this.removeEventListener('mousemove', holder.move);
        this.removeEventListener('touchend', holder.end);
        this.removeEventListener('mouseup', holder.end);
      }
    };
    node.addEventListener('mousedown', function () {
      holder.holdstart();
    });
    node.addEventListener('touchdown', function () {
      holder.holdstart();
    });
  }
  ui.rotate = function (node, speed) {
    var nodename = this.createName();
    node.setAttribute('id', 'ui-' + nodename);
    var time = 1 / speed;
    node.style.transition = 'transform ' + time + 's linear';
    node.style.transform = 'rotate(0deg)';
    var rotation = 360;
    this.global[nodename] = node;
    node.rotatetimer = setInterval(function () {
      rotation += 360;
      node.style.transform = 'rotate(' + rotation + 'deg)';
    }, time * 1000);
    node.style.transform = 'rotate(' + rotation + 'deg)';
  }
  ui.guide = function (guidename) {
  }
  ui.snap = function (node, direction, fun) {
  }
  ui.swipe = function (node, direction, fun) {
    if (!node.tagName) {
      node = g(node);
    }
    var directionid = 'sw-' + direction;
    node[directionid] = {
      startx: 0,
      starty: 0,
      x: 0,
      y: 0,
      threshold: 240,
      draggable: false,
      timerange: 350,
      dir: direction,
      timer: 0,
      outoftime: false,
      reset: function () {
        this.x = 0;
        this.y = 0;
        this.startx = 0;
        this.starty = 0;
      },
      setThreshold: function (val) {
        this.threshold = val;
        return this;
      },
      setDraggable: function (val, lockx, locky) {
        node.style.transform = 'translate(0,0)';
        this.draggable = val;
        if (lockx) {
          this.lockx = true;
        }
        if (locky) {
          this.locky = true;
        }
        return this;
      },
      setSnap: function (val) {
        this.snaprange = val;
        if (this.direction == 'left' || this.direction == 'right') {
          this.locky = true;
        }
        if (this.direction == 'up' || this.direction == 'down') {
          this.lockx = true;
        }
        return this;
      },
      setTimerange: function (val) {
        this.timerange = val;
        return this;
      },
      touchstart: function () {
        if (event.changedTouches.length != 1) {
          return;
        }
        var tc0 = event.changedTouches[0];
        if (tc0) {
          node[directionid].startx = tc0.pageX;
          node[directionid].starty = tc0.pageY;
          node[directionid].x = tc0.pageX;
          node[directionid].y = tc0.pageY;
        } else {
          node[directionid].outoftime = true;
          return;
        }
        node[directionid].outoftime = false;
        timer = setTimeout(function () {
          node[directionid].outoftime = true;
        }, node[directionid].timerange);
      },
      touchmove: function () {
        if (event.changedTouches.length != 1) {
          return;
        }
        var tc0 = event.changedTouches[0];
        if (tc0) {
          node[directionid].x = tc0.pageX;
          node[directionid].y = tc0.pageY;
          if (node[directionid].draggable) {
            if (node[directionid].lockx && node[directionid].locky) {
              return;
            } else if (node[directionid].lockx) {
              node.style.transform = 'translate(0px,' + (tc0.pageY - node[directionid].starty) + 'px)';
            } else if (node[directionid].locky) {
              node.style.transform = 'translate(' + (tc0.pageX - node[directionid].startx) + 'px,0px)';
            } else {
              node.style.transform = 'translate(' + (tc0.pageX - node[directionid].startx) + 'px,' + (tc0.pageY - node[directionid].starty) + 'px)';
            }
          }
        }
      },
      touchend: function () {
        if (!node[directionid].outoftime) {
          if (direction == 'down') {
            if (Math.abs(node[directionid].startx - node[directionid].x) < 220) {
              if (node[directionid].y - node[directionid].starty > node[directionid].threshold) {
                node[directionid].trigger();
              }
            }
          }
          if (direction == 'up') {
            if (Math.abs(node[directionid].startx - node[directionid].x) < 220) {
              if (node[directionid].starty - node[directionid].y > node[directionid].threshold) {
                node[directionid].trigger();
              }
            }
          }
          if (direction == 'left') {
            if (Math.abs(node[directionid].starty - node[directionid].y) < 150) {
              if (node[directionid].startx - node[directionid].x > node[directionid].threshold) {
                node[directionid].trigger();
              }
            }
          }
          if (direction == 'right') {
            if (Math.abs(node[directionid].starty - node[directionid].y) < 150) {
              if (node[directionid].x - node[directionid].startx > node[directionid].threshold) {
                node[directionid].trigger();
              }
            }
          }
        }
        if (node[directionid].draggable) {
          node.style.transform = 'translate(0px,0px)';
        }
        node[directionid].outoftime = false;
        clearTimeout(node[directionid].timer);
      },
      trigger: fun
    };
    node.addEventListener('touchstart', node[directionid].touchstart);
    node.addEventListener('touchmove', node[directionid].touchmove);
    node.addEventListener('touchend', node[directionid].touchend);
    return node[directionid];
  }
  ui.copy = function (valuetocopy) {
    var ip = document.createElement('textarea');
    document.body.appendChild(ip);
    ip.value = valuetocopy;
    if (document.selection) {
      var range = document.body.createTextRange();
      range.moveToElementText(ip);
      range.select();
    } else if (window.getSelection) {
      var range = document.createRange();
      range.selectNode(ip);
      window.getSelection().removeAllRanges();
      window.getSelection().addRange(range);
    }
    document.execCommand('copy');
    ip.remove();
  }
  ui.infinity = function (container, endpoint, calb) {
    container.threshold = 125;
    container.setThreshold = function (i) {
      this.threshold = i;
      return this;
    }
    container.setAnim = function (anim) {
      if (anim) {
        this.onloading = anim;
      } else {
        this.onloading = function () {
          if (this.loading) {
            var dv = document.createElement('div');
            dv.style.padding = '10px;';
            dv.style.textAlign = 'center';
            dv.innerHTML = '<i class="spinner-border"> </i>';
            dv.className = 'infinity-spinner';
            this.appendChild(dv);
          } else {
            setTimeout(function () {
              container.querySelector('.infinity-spinner').remove();
            }, 10);
          }
        }
      }
      return this;
    }
    container.setPager = function (pname, start, offset) {
      if (!offset) {
        offset = 1;
      }
      if (!start) {
        start = 0;
      }
      if (!pname) {
        pname = '&p=';
      } else {
        pname = '&' + pname + '=';
      }
      this.pager = pname + start;
      this.onpagechange = function () {
        start += offset;
        this.pager = pname + start;
      }
      return this;
    }
    container.pager = '';
    if (!calb) {
      calb = function (d) {
        this.innerHTML += d;
      }
    }
    container.ended = false;
    if (endpoint) {
      container.onautoload = function (rs) {
        if (rs != '') {
          calb.apply(container, [
            rs
          ]);
        } else {
          this.ended = true;
        }
      };
      var overflowdefalut = getComputedStyle(container);
      if (overflowdefalut.overflowY == 'visible' || overflowdefalut.overflowY == 'hidden') {
        window.addEventListener('scroll', function () {
          if (container.loading || container.ended) {
            return;
          }
          if (document.body.scrollTop + container.threshold > document.body.scrollHeight - document.body.clientHeight) {
            container.loading = true;
            if (container.onloading) {
              container.onloading();
            }
            if (typeof endpoint == 'function') {
              endpoint();
              container.loading = false;
              if (container.onloading) {
                container.onloading();
              }
              if (container.onpagechange) {
                container.onpagechange();
              }
            } else
            ajax(endpoint + container.pager, function (down) {
              container.onautoload(down);
              container.loading = false;
              if (container.onloading) {
                container.onloading();
              }
              if (container.onpagechange) {
                container.onpagechange();
              }
            });
          }
        });
      } else
      container.addEventListener('scroll', function () {
        if (this.loading || container.ended) {
          return;
        }
        if (this.scrollTop + this.threshold > this.scrollHeight - this.clientHeight) {
          this.loading = true;
          if (this.onloading) {
            this.onloading();
          }
          if (typeof endpoint == 'function') {
            endpoint();
            container.loading = false;
            if (container.onloading) {
              container.onloading();
            }
            if (container.onpagechange) {
              container.onpagechange();
            }
          } else
          ajax(endpoint + this.pager, function (down) {
            container.onautoload(down);
            container.loading = false;
            if (container.onloading) {
              container.onloading();
            }
            if (container.onpagechange) {
              container.onpagechange();
            }
          });
        }
      });
    }
    return container;
  }
  ui.table = function () {
    var cols = arguments.length;
    var nodename = this.createName();
    var table = document.createElement('table');
    table.appendChild(document.createElement('tr'));
    this.global[nodename] = table;
    for (var i = 0; i < cols; i++) {
      var th = document.createElement('th');
      th.innerHTML = arguments[i];
      table.children[0].appendChild(th);
    }
    table.row = function () {
      var tr = document.createElement('tr');
      for (var i = 0; i < arguments; i++) {
        var td = document.createElement('td');
        td.innerHTML = arguments[i];
        tr.appendChild(td);
      }
      table.appendChild(tr);
    }
    table.destroy = function () {
      table.remove();
      delete ui.global[nodename];
    }
    return table;
  }
  ui.dropdown = function (node) {
    var nodename = this.createName();
    var menu = document.createElement('div');
    $(menu).css({
      'background': 'white',
      'border-radius': '6px;'
    });
  }
  ui.resize = function (node1, node2, orient) {
    var modulename = this.createName();
    if (node1.nE() !== node2) {
      return console.log('node1 is not node2 previousElementSibling');
    }
    if (orient == 'horizontal') {
      var css1 = getComputedStyle(node1);
      var css2 = getComputedStyle(node2);
      this.global[modulename] = {
        ihr1: parseInt(css1.width),
        ihr2: parseInt(css2.width),
        nd1: node1,
        nd2: node2,
        widthtotal: parseInt(css1.width) + parseInt(css2.width),
        width1cr: parseInt(css1.width),
        width2cr: parseInt(css2.width),
        resize: function () {
          var mv = event.pageX - this.crX;
          this.width1cr += mv;
          this.nd1.style.width = this.width1cr + 'px';
          this.width2cr -= mv;
          this.nd2.style.width = this.width2cr + 'px';
          this.crX = event.pageX;
        }
      };
      var div = document.createElement('div');
      this.global[modulename].resizediv = div;
      $(div).css({
        width: '2px',
        height: '100%',
        position: 'static',
        border: '1px solid gray',
        borderWidth: '0 1px 0 1px',
        cursor: 'col-resize'
      });
      div.onmousedown = function () {
        ui.global[modulename].crX = event.pageX;
        ui.global[modulename].width1cr = parseInt(getComputedStyle(node1).width);
        ui.global[modulename].width2cr = parseInt(getComputedStyle(node2).width);
        var funmm = function () {
          ui.global[modulename].resize();
        }
        document.addEventListener('mousemove', funmm);
        var funmv = function () {
          document.removeEventListener('mousemove', funmm);
          document.removeEventListener('mousemove', funmv);
        }
        document.addEventListener('mouseup', funmv);
      }
      node1.parentElement.insertBefore(div, node2);
    } else
    if (orient == 'vertical') {
      var css1 = getComputedStyle(node1);
      var css2 = getComputedStyle(node2);
      this.global[modulename] = {
        ihr1: parseInt(css1.height),
        ihr2: parseInt(css2.height),
        nd1: node1,
        nd2: node2,
        widthtotal: parseInt(css1.height) + parseInt(css2.height),
        width1cr: parseInt(css1.height),
        width2cr: parseInt(css2.height),
        resize: function () {
          var mv = event.pageY - this.crY;
          this.width1cr += mv;
          this.nd1.style.height = this.width1cr + 'px';
          this.width2cr -= mv;
          this.nd2.style.height = this.width2cr + 'px';
          this.crY = event.pageY;
        }
      };
      var div = document.createElement('div');
      this.global[modulename].resizediv = div;
      $(div).css({
        width: '100%',
        height: '2px',
        position: 'static',
        border: '1px solid gray',
        borderWidth: '0 1px 0 1px',
        cursor: 'row-resize'
      });
      div.onmousedown = function () {
        ui.global[modulename].crY = event.pageY;
        ui.global[modulename].width1cr = parseInt(getComputedStyle(node1).height);
        ui.global[modulename].width2cr = parseInt(getComputedStyle(node2).height);
        var funmm = function () {
          ui.global[modulename].resize();
        }
        document.addEventListener('mousemove', funmm);
        var funmv = function () {
          document.removeEventListener('mousemove', funmm);
          document.removeEventListener('mousemove', funmv);
        }
        document.addEventListener('mouseup', funmv);
      }
      node1.parentElement.insertBefore(div, node2);
    }
  }
  ui.press = function (btn) {
    btn.oldMk = btn.innerHTML;
    btn.innerHTML = '<span class=\'spinner-border\'></span>';
    btn.disabled = true;
    btn.end = function () {
      this.innerHTML = this.oldMk;
      this.disabled = false;
    }
  }
  ui.uploadimg = function (calb, previewer, customhandler, selected) {
    var ip = document.createElement('input');
    ip.setAttribute('type', 'file');
    ip.setAttribute('accept', 'image/*');
    ip.style.display = 'none';
    ip.addEventListener('change', function () {
      if (selected != null) selected();
      if (this.files != null) {
        if (this.files[0].size > 4000000) {
          alert('Kích thước tối đa 4MB');
          return;
        } else {
          var FR = new FileReader();
          FR.addEventListener('load', function (e) {
            var base64 = e.target.result;
            if (previewer != null) {
              previewer.src = base64;
            }
            var http = new XMLHttpRequest();
            var url = '/index.php';
            var params = (customhandler || 'ajax=cboximg') + '&imgdata=' + base64;
            http.open('POST', url, true);
            http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            http.onreadystatechange = function () {
              if (http.readyState == 4 && http.status == 200) {
                ip.remove();
                var x = http.responseText.split(':::');
                if (x[0] == 'filename') {
                  if (calb != null) {
                    calb(x[1]);
                  }
                } else {
                  console.log(x[1]);
                }
              }
            }
            http.send(params);
          });
          FR.readAsDataURL(this.files[0]);
        }
      }
    });
    ip.click();
  }
  ui.preloader = function () {
    var d = document.createElement('div');
    d.setAttribute('style', 'position:fixed;top:0;left:0;width:100vw;height:100vh;background:white;z-index:9999');
    d.innerHTML = '<div style=\'position:absolute;top:50%;left:50%;transform:translate(-50%, -50%)\'>' +
    '<div class=\'spinner-border\' style=\'width:200px;height:200px;\'></div></div>';
    document.body.appendChild(d);
  }
  ui.swiftload = function (url, scrollto, onload) {
    if (!history || !history.pushState) {
      return;
    }
    if (url.tagName == 'A') {
      if (!window.setting || !window.setting.enableswiftload) return;
      url = url.href;
      event.preventDefault();
    }
    var args = arguments;
    var purl = url.contain('?') ? url + '&sw=' : url + '?~sw-~';
    var swiftrotator = new XMLHttpRequest();
    swiftrotator.open('GET', purl, true);
    swiftrotator.setRequestHeader('SWIFT-ROTATOR', new Date().getTime());
    swiftrotator.onreadystatechange = function () {
      if (swiftrotator.readyState == 4 && swiftrotator.status == 200) {
        window.stop();
        var lastscr = document.body.scrollTop;
        if (ui.swiftload.anistart) {
          if (ui.swiftload.aniend)
          ui.swiftload.aniend();
        }
        history.replaceState({
          'html': g('inner').innerHTML,
          'pageTitle': document.title,
          'scrolllast': lastscr
        }, '');
        g('inner').innerHTML = '<br>' + swiftrotator.responseText;
        if (onload) {
          onload();
        }
        var arr = g('inner').getElementsByTagName('script');
        try {
          ga('set', 'page', url);
          ga('send', 'pageview');
        } catch (excep) {
        }
        document.title = 'Sáng Tác Việt - Nền tảng văn học mạng mở mới';
        history.pushState({
          'html': g('inner').innerHTML,
          'pageTitle': document.title,
          'scrolllast': lastscr
        }, '', url);
        g('inner').setAttribute('style', '');
        g('full').setAttribute('style', 'min-height:99vh');
        for (var n = 0; n < arr.length; n++) {
          if (arr[n].hasAttribute('src')) {
            (function (d, script, src) {
              if (q('[uniq="' + src + '"]') [0] != null) return;
              script = d.createElement('script');
              script.type = 'text/javascript';
              script.async = true;
              script.onload = function () {
              };
              script.setAttribute('uniq', src);
              script.src = src;
              d.getElementsByTagName('head') [0].appendChild(script);
            }(document, null, arr[n].src));
          }
          else (function () {
            try {
              eval.apply(this, arguments);
            } catch (exce) {
              var jsd = arguments[0];
              setTimeout(function () {
                (function () {
                  try {
                    eval.apply(this, arguments);
                  } catch (exce2) {
                    console.log('failed');
                    console.log(exce2);
                  }
                }(jsd))
              }, 200);
            }
          }(arr[n].innerHTML));
        }
        if (scrollto) {
          $([document.documentElement,
          document.body]).animate({
            scrollTop: $('#' + scrollto).offset().top + 50
          }, 200);
        } else {
          document.body.scrollTop = 0;
        }
        if (window.ismenu2show) {
          g('usermenupos').children[0].style.display = 'none';
          ismenu2show = false;
        }
      } else if (swiftrotator.readyState == 4 && swiftrotator.status == 502) {
        setTimeout(function () {
          ui.swiftload(args);
        }, 1000);
      }
    }
    swiftrotator.send();
    if (ui.swiftload.anistart) {
      ui.swiftload.anistart();
    }
  }
  ui.scrollto = function (ele, offset) {
    $([document.documentElement,
    document.body]).animate({
      scrollTop: $('#' + ele).offset().top + (offset || 0)
    }, 200);
  }
  function getCoords(elem) {
    var box = elem.getBoundingClientRect();
    var body = document.body;
    var docEl = document.documentElement;
    var scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
    var scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;
    var clientTop = docEl.clientTop || body.clientTop || 0;
    var clientLeft = docEl.clientLeft || body.clientLeft || 0;
    var top = box.top + scrollTop - clientTop;
    var left = box.left + scrollLeft - clientLeft;
    return {
      top: Math.round(top),
      left: Math.round(left)
    };
  }
  ui.stickable = function (stickele, classname) {
    if (!stickele.push) {
      stickele = [
        stickele
      ];
    }
    var sticky = getCoords(stickele[0]).top;
    for (var i = 0; i < stickele.length; i++) {
      stickele[i].style.width = getComputedStyle(stickele[i]).width;
    }
    window.addEventListener('scroll', function () {
      if (window.pageYOffset >= sticky) {
        for (var i = 0; i < stickele.length; i++) {
          stickele[i].classList.add(classname);
        }
      } else {
        for (var i = 0; i < stickele.length; i++) {
          stickele[i].classList.remove(classname);
        }
      }
    });
  }
  window.onpopstate = function (e) {
    window.stop();
    if (e.state) {
      document.getElementById('inner').innerHTML = e.state.html;
      document.title = e.state.pageTitle;
      var arr = g('inner').getElementsByTagName('script');
      g('inner').setAttribute('style', '');
      g('full').setAttribute('style', '');
      for (var n = 0; n < arr.length; n++) {
        (function () {
          try {
            eval.apply(this, arguments);
          } catch (exce) {
          }
        }(arr[n].innerHTML));
      }
      setTimeout(function () {
      }, 100);
    }
  };
  ui.autoresize = function (ip) {
    ip.style.height = 'auto';
    ip.style.overflowY = 'hidden';
    ip.addEventListener('input', function () {
      this.style.height = 'auto';
      this.style.height = (this.scrollHeight) + 'px';
    }, false);
  }
  ui.toggle = function (div) {
    div.currentMode = getComputedStyle(div).display;
    if (div.currentMode == 'none') {
      return function (force) {
        if (force == null) {
          if (div.currentMode == 'block') {
            div.currentMode = 'none';
          } else {
            div.currentMode = 'block';
          }
        } else {
          if (force) {
            div.currentMode = 'block';
          } else {
            div.currentMode = 'none';
          }
        }
        div.style.display = div.currentMode;
      }
    } else {
      div.defaultMode = div.currentMode;
      return function (force) {
        if (force == null) {
          if (div.currentMode == div.defaultMode) {
            div.currentMode = 'none';
          } else {
            div.currentMode = div.defaultMode;
          }
        } else {
          if (force) {
            div.currentMode = div.defaultMode;
          } else {
            div.currentMode = 'none';
          }
        }
        div.style.display = div.currentMode;
      }
    }
  }
  ui.clickoutside = function (div, condition) {
    if (condition == null) {
      div.addEventListener('click', function () {
        event.stopPropagation();
      });
      window.addEventListener('click', function () {
        div.style.display = 'none';
      });
    } else {
      div.addEventListener('click', function () {
        if (!window[condition])
        event.stopPropagation();
      });
      window.addEventListener('click', function () {
        if (!window[condition])
        div.style.display = 'none';
      });
    }
  }
  window.pl = 'scr';
  ui.expand = function (container) {
    if (container.getAttribute('isexpanded')) {
      return;
    }
    container.setAttribute('isexpanded', 'false');
    container.style.overflow = 'hidden';
    var sty = getComputedStyle(container);
    var mh = sty.maxHeight;
    var expander = document.createElement('div');
    expander.style.textAlign = 'center';
    expander.style.backgroundColor = sty.backgroundColor;
    expander.style.color = sty.color;
    expander.style.padding = '6px';
    expander.innerHTML = '<i class="fas fa-chevron-down"></i>';
    expander.addEventListener('click', function () {
      var expd = container.getAttribute('isexpanded');
      if (expd == 'true') {
        container.style.maxHeight = mh;
        container.setAttribute('isexpanded', 'false');
        this.innerHTML = '<i class="fas fa-chevron-down"></i>';
      } else {
        container.style.maxHeight = 'none';
        container.setAttribute('isexpanded', 'true');
        this.innerHTML = '<i class="fas fa-chevron-up"></i>';
      }
    });
    container.parentElement.insertBefore(expander, container.nextSibling);
  }
  ui.sh = function (nindex) {
    document.addEventListener(pl + 'oll', nindex);
  }
  ui.tab = function (tabber, tabdiv) {
    for (var i = 0; i < tabber.children.length; i++) {
      tabber.children[i].addEventListener('click', function () {
        for (var j = 0; j < tabber.children.length; j++) {
          if (tabber.children[j] == this) {
            this.classList.add('active');
            tabdiv.children[j].style.display = 'block';
          } else {
            tabber.children[j].classList.remove('active');
            tabdiv.children[j].style.display = 'none';
          }
        }
      });
    }
  }
  ui.stab = function (tabber, tabdiv) {
    tabdiv.setAttribute('style', 'transform:translateX(0px);transition:transform .4s;display:flex;flex-wrap:nowrap;');
    tabdiv.parentElement.style.overflowX = 'hidden';
    tabdiv.style.width = '' + tabber.children.length + '00%';
    var widthspl = 100 / tabber.children.length;
    for (var i = 0; i < tabber.children.length; i++) {
      tabber.children[i].addEventListener('click', function () {
        for (var j = 0; j < tabber.children.length; j++) {
          if (tabber.children[j] == this) {
            this.classList.add('active');
            tabdiv.style.transform = 'translateX(-' + (j * widthspl) + '%)';
          } else {
            tabber.children[j].classList.remove('active');
          }
        }
      });
    }
    tabdiv.nextTab = function () {
      for (var j = 0; j < tabber.children.length; j++) {
        if (tabber.children[j].className.contain('active')) {
          if (j + 1 < tabber.children.length) {
            tabber.children[j + 1].click();
            return;
          }
        }
      }
    }
    tabdiv.prevTab = function () {
      for (var j = 0; j < tabber.children.length; j++) {
        if (tabber.children[j].className.contain('active')) {
          if (j - 1 > - 1) {
            tabber.children[j - 1].click();
            return;
          }
        }
      }
    }
    ui.swipe(tabdiv, 'left', function () {
      tabdiv.nextTab();
    }).setThreshold(150);
    ui.swipe(tabdiv, 'right', function () {
      tabdiv.prevTab();
    }).setThreshold(150);
  }
  ui.select = function () {
    var md = createModal('Tùy chọn');
    var bd = md.body();
    md.option = function (val, text) {
      var opt = document.createElement('div');
      opt.className = 'seloption';
      opt.innerHTML = text;
      opt.setAttribute('value', val);
      opt.addEventListener('click', function () {
        md.proc(this.getAttribute('value'));
      });
      bd.appendChild(opt);
    }
    return md;
  }
  ui.minisearch = function (val) {
    clearTimeout(window.minisearchtimeout);
    window.minisearchtimeout = setTimeout(function () {
      ajax('ajax=minisearch&search=' + val, function (down) {
        g('searchbox').innerHTML = down;
      });
    }, 500);
  }
  function changebg(e) {
    var color = e.style.backgroundColor;
    localStorage.setItem('backgroundcolor', color);
    try {
      g(contentcontainer).style.backgroundColor = color;
      g('full').style.backgroundColor = (color.split(')') [0] + ', 0.7)').replace('rgb', 'rgba');
    } catch (x) {
    }
  }
  function changebgx(e) {
    var color = e.style.color;
    localStorage.setItem('fontcolor', color);
    try {
      g(contentcontainer).style.color = color;
    } catch (x) {
    }
  }
  function changefontsize(e) {
    var def = e.value;
    try {
      g(contentcontainer).style.fontSize = def + 'px';
    } catch (x) {
    }
    localStorage.setItem('fontsize', def);
  }
  function changelineheight(e) {
    var def = e.value;
    try {
      g(contentcontainer).style.lineHeight = def;
    } catch (x) {
    }
    localStorage.setItem('fontsize2', def);
  }
  function componentFromStr(numStr, percent) {
    var num = Math.max(0, parseInt(numStr, 10));
    return percent ? Math.floor(255 * Math.min(100, num) / 100) : Math.min(255, num);
  }
  function rgbToHex(rgb) {
    var rgbRegex = /^rgb\(\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*\)$/;
    var result,
    r,
    g,
    b,
    hex = '';
    if ((result = rgbRegex.exec(rgb))) {
      r = componentFromStr(result[1], result[2]);
      g = componentFromStr(result[3], result[4]);
      b = componentFromStr(result[5], result[6]);
      hex = '#' + (16777216 + (r << 16) + (g << 8) + b).toString(16).slice(1);
    }
    return hex || rgb;
  }
  function rgbToInt(rgb) {
    var rgbRegex = /^rgb\(\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*,\s*(-?\d+)(%?)\s*\)$/;
    var result,
    r,
    g,
    b,
    hex = '';
    if ((result = rgbRegex.exec(rgb))) {
      r = componentFromStr(result[1], result[2]);
      g = componentFromStr(result[3], result[4]);
      b = componentFromStr(result[5], result[6]);
      hex = (255 << 24) + (r << 16) + (g << 8) + b;
    }
    return hex;
  }
  function loadConfig() {
    var fontc = localStorage.getItem('fontcolor');
    var bg = localStorage.getItem('backgroundcolor');
    var fonts = localStorage.getItem('fontsize');
    var lh = localStorage.getItem('fontsize2');
    var fontfam = localStorage.getItem('fontfamily');
    var padding = localStorage.getItem('padding');
    var align = localStorage.getItem('textalign');
    var setting = localStorage.getItem('setting');
    if (setting != null) {
      window.setting = JSON.parse(setting);
    } else {
      window.setting = {
        autosave: true,
        autosync: false,
        autofootprint: false,
        allowchiname: true,
        peoplefilter: true,
        factionfilter: true,
        allowtaptoedit: true,
        scopefilter: true,
        skillfilter: true,
        skilluppercase: false,
        allownamev3: true,
        allowanalyzerupdate: true
      };
      localStorage.setItem('setting', JSON.stringify(window.setting));
    }
    if (window.setting.allowunitymode) {
      $('#btnunitymode').css({
        display: 'block',
        opacity: 1
      });
      document.addEventListener('scroll', ui.unity.scroll);
      document.addEventListener('touchstart', ui.unity.touchstart);
      document.addEventListener('touchmove', ui.unity.touchmove);
    }
    var isstylable = false;
    var stylablediv = q('.contentbox');
    if (stylablediv.length > 0) {
      isstylable = true;
    }
    if (isstylable) {
      stylablediv.forEach(function (e) {
        if (fontc != null) {
          try {
            e.style.color = fontc;
          } catch (e2) {
          }
        }
        if (bg != null) {
          try {
            e.style.backgroundColor = bg;
            g('full').style.backgroundColor = (bg.split(')') [0] + ', 0.7)').replace('rgb', 'rgba');
            if (getCookie('theme') == 'light') {
              g('tm-bot-nav').className += ' reader-add';
              g('mainbar').className += ' reader-add';
              g('commentportion').className += ' reader-add';
              g('tm-credit-section').className += ' reader-add';
              g('full').appendChild(g('tm-credit-section'));
              g('id').className += ' reader-add';
            }
            var mt = document.createElement('meta');
            mt.setAttribute('name', 'theme-color');
            mt.setAttribute('content', rgbToHex(bg));
            document.head.appendChild(mt);
          } catch (e2) {
          }
        }
        if (fonts != null) {
          try {
            e.style.fontSize = fonts + 'px';
            g('changefs').value = fonts;
          } catch (e2) {
          }
        }
        if (lh != null) {
          try {
            e.style.lineHeight = lh;
            g('changefs2').value = lh;
          } catch (e2) {
          }
        }
        if (fontfam != null) {
          if (!window.lockCtp)
          try {
            e.style.fontFamily = fontfam;
            g('selfont').value = fontfam;
          } catch (e2) {
          }
        }
        if (padding != null) {
          try {
            e.style.padding = padding;
          } catch (e2) {
          }
        }
        if (align != null) {
          try {
            e.style.textAlign = align;
          } catch (e2) {
          }
        }
      });
    }
    var timeelap = document.querySelectorAll('.timeelap');
    timeelap.forEach(function (e) {
      e.innerHTML = timeElapsed(e.innerHTML, true);
    });
    setTimeout(function () {
      var timeelap = document.querySelectorAll('.timeelap');
      timeelap.forEach(function (e) {
        e.innerHTML = timeElapsed(e.innerHTML, true);
      });
    }, 3000)
    if (isstylable)
    try {
      ui.unity(true);
    } catch (xxxxx) {
    }
  }
  function notification() {
    var md = createModal('Thông báo');
    md.size('md');
    md.body().innerHTML = '<center><br><span class=\'spinner-border\'></span><br>Đang tải thông báo...</center>';
    md.body().style.maxHeight = '325px';
    md.body().style.overflowY = 'auto';
    var xhttp = new XMLHttpRequest();
    var params = 'ajax=notification';
    xhttp.open('POST', '/index.php', true);
    xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        md.body().innerHTML = this.responseText;
        g('notifmarker').removeAttribute('havenotif');
        ui.infinity(md.body(), 'ajax=notification').setAnim().setPager('p', 1);
      }
    };
    xhttp.send(params);
    md.show();
  }
  function closemenuwindow() {
    g('menunavigator').style.display = 'none';
    ismenushow = false;
  }
  var ismenushow = false;
  function showmenu(a) {
    if (ismenushow) {
      a.children[1].style.display = 'none';
      ismenushow = false;
    } else {
      a.children[1].style.display = 'block';
      ismenushow = true;
    }
  }
  function decreaseFontsize() {
    var curr = parseInt(g('changefs').value);
    curr--;
    if (curr < 10) curr = 10;
    try {
      g(contentcontainer).style.fontSize = curr + 'px';
      g('changefs').value = curr;
    } catch (x) {
    }
    localStorage.setItem('fontsize', curr);
  }
  function increaseFontsize() {
    var curr = parseInt(g('changefs').value);
    curr++;
    if (curr > 50) curr = 50;
    try {
      g(contentcontainer).style.fontSize = curr + 'px';
      g('changefs').value = curr;
    } catch (x) {
    }
    localStorage.setItem('fontsize', curr);
  }
  function changealign(el) {
    try {
      g(contentcontainer).style.textAlign = el.getAttribute('val');
      localStorage.setItem('textalign', el.getAttribute('val'));
    } catch (e) {
      console.log(e);
    }
  }
  function decreaseLineheight() {
    var curr = parseFloat(g('changefs2').value);
    curr -= 0.1;
    if (curr < 0.5) curr = 0.5;
    curr = curr.toFixed(1);
    try {
      g(contentcontainer).style.lineHeight = curr;
      g('changefs2').value = curr;
    } catch (x) {
    }
    localStorage.setItem('fontsize2', curr);
  }
  function increasepadding() {
    try {
      g(contentcontainer).style.padding = (parseInt(g(contentcontainer).style.padding) + 4) + 'px';
      localStorage.setItem('padding', g(contentcontainer).style.padding);
    } catch (x) {
    }
  }
  function decreasepadding() {
    try {
      g(contentcontainer).style.padding = (parseInt(g(contentcontainer).style.padding) - 4) + 'px';
      localStorage.setItem('padding', g(contentcontainer).style.padding);
    } catch (x) {
    }
  }
  function increaseLineheight() {
    var curr = parseFloat(g('changefs2').value);
    curr += 0.1;
    if (curr > 3) curr = 3;
    curr = curr.toFixed(1);
    try {
      g(contentcontainer).style.lineHeight = curr;
      g('changefs2').value = curr;
    } catch (x) {
    }
    localStorage.setItem('fontsize2', curr);
  }
  function fastCreateN(e) {
    g('fastgentext').value = titleCase(e.value);
  }
  function showConfigBox() {
    if (g('configBox').style.display == 'block')
    g('configBox').style.display = 'none';
     else g('configBox').style.display = 'block';
  }
  ui.unity = function (onoff) {
    if (!window.setting.allowunitymode) {
      return;
    }
    if (!/truyen\/\w+\/\d\/\d+\/[1-9]/.test(location.href)) return;
    if (onoff == null) {
      var onoff2 = localStorage.getItem('unitymode');
      if (onoff2 == null) {
        localStorage.setItem('unitymode', 'true');
        onoff = true;
      } else if (onoff2 == 'false') {
        onoff = true;
        localStorage.setItem('unitymode', 'true');
      } else if (onoff2 == 'true') {
        onoff = false;
        localStorage.setItem('unitymode', 'false');
      }
    } else {
      var onoff2 = localStorage.getItem('unitymode');
      if (onoff2 == null) {
        localStorage.setItem('unitymode', 'false');
        onoff = false;
      } else if (onoff2 == 'true') {
        onoff = true;
      } else if (onoff2 == 'false') {
        onoff = false;
      }
    }
    if (onoff) {
      g('btnnextchapter').style.display = 'block';
      g('btnprevchapter').style.display = 'block';
      g('commentportion').style.display = 'none';
      g('tm-credit-section').style.display = 'none';
      g('breadcum').className = '';
      var mct = g(contentcontainer).parentElement;
      var cn = mct;
      var cpst;
      while (cn.nE() != null && cn.nE().nE() != null && cn.nE().nE().nE() != null) {
        cn = cn.nE();
        cpst = getComputedStyle(cn);
        if (cpst.display != 'none') {
          cn.defdis = cpst.display;
          ui.unity.hidden.push(cn);
          cn.style.display = 'none';
        }
      }
      cn = mct;
      while (cn.pE() != null) {
        cn = cn.pE();
        if (cn.id == 'breadcum') continue;
        cpst = getComputedStyle(cn);
        if (cpst.display != 'none') {
          cn.defdis = cpst.display;
          ui.unity.hidden.push(cn);
          cn.style.display = 'none';
        }
      }
      cn = g('inner');
      while (cn.pE() != null) {
        cn = cn.pE();
        cpst = getComputedStyle(cn);
        if (cpst.display != 'none') {
          cn.defdis = cpst.display;
          ui.unity.hidden.push(cn);
          cn.style.display = 'none';
        }
      }
    } else {
      g('btnnextchapter').style.display = 'none';
      g('btnprevchapter').style.display = 'none';
      g('commentportion').style.display = 'block';
      g('tm-credit-section').style.display = 'block';
      g('breadcum').className = 'container bg-light tm-reader-top-br';
      while (ui.unity.hidden.length > 0) {
        var ele = ui.unity.hidden.pop();
        ele.style.display = ele.defdis;
      }
    }
  }
  ui.unity.hidden = [
  ];
  ui.unity.scroll = function () {
    if (document.body.scrollTop + document.body.clientHeight > document.body.scrollHeight - 100) {
      $('#btnnextchapter').css({
        background: 'wheat',
        opacity: 1
      });
      if (!window.setting.blockunitymerge)
      ui.unity.getnextchapterkey();
      document.addEventListener('touchstart', ui.unity.touchstart);
    } else {
      document.removeEventListener('touchstart', ui.unity.touchstart);
      $('#btnnextchapter').css({
        background: 'none',
        opacity: 0.3
      });
    }
    if (document.body.scrollTop < 80) {
      $('#btnprevchapter').css({
        background: 'wheat',
        opacity: 1
      });
      $('#btnunitymode').css({
        display: 'block',
        display: 'block'
      });
    } else {
      $('#btnprevchapter').css({
        background: 'none',
        opacity: 0.3
      });
      $('#btnunitymode').css({
        display: 'none',
        display: 'none'
      });
    }
  }
  ui.web = {
  };
  ui.web.s = function () {
    ui.web.s.t += (window.scrollY - ui.web.s.h);
    ui.web.s.h = window.scrollY;
  }
  ui.web.s.h = 0;
  ui.web.s.t = 0;
  ui.unity.touchstart = function (e) {
    ui.unity.starty = e.targetTouches ? e.targetTouches[0].screenY : e.screenY;
  }
  ui.unity.threshold = (window.innerHeight || document.body.clientHeight) * 0.25;
  ui.unity.touchmove = function (e) {
    if (document.body.scrollTop + (window.innerHeight || document.body.clientHeight) > document.body.scrollHeight - 10) {
      var endy = e.targetTouches ? e.targetTouches[0].screenY : e.screenY;
      if (window.setting.blockunitymerge && ui.unity.starty - endy > ui.unity.threshold) {
        g('navnexttop').click();
      }
    }
  }
  ui.unity.getnextchapterkey = function () {
    if (typeof newchapid == 'undefined' || newchapid == '0') {
      return;
    }
    ui.unity._getnextchapterkey = ui.unity.getnextchapterkey;
    ui.unity.getnextchapterkey = function () {
    };
    g('navnexttop').setAttribute('href', g('navnextbot').href);
    var contentfather = g('content-container');
    var newchapcontent = document.createElement('div');
    newchapcontent.setAttribute('style', g(contentcontainer).getAttribute('style'));
    newchapcontent.setAttribute('id', contentcontainer);
    newchapcontent.setAttribute('cid', newchapid);
    newchapcontent.className = 'contentbox';
    newchapcontent.innerHTML = '<br><center><span class="spinner-border"></span><br>Đang tải nội dung chương...</center><br>';
    g(contentcontainer).setAttribute('id', 'oldmaincontent-' + currentid);
    contentfather.appendChild(newchapcontent);
    ui.unity.loadnextchapter(newchapcontent);
  }
  ui.unity.loadnextchapter = function (newchapcontent) {

  }
  ui.win = {
  }
  ui.win.close = function (n) {
    if (n.className == 'window') {
      n.onclose();
    } else
    {
      while (n.className != 'window') {
        n = n.parentElement;
      }
      n.onclose();
    }
  }
  ui.win.fullscreen = function (n) {
    while (n.className != 'window') {
      n = n.parentElement;
    }
    n.children[0].classList.toggle('full');
    try {
      if (n.hasAttribute('fullscreen')) {
        n.removeAttribute('fullscreen');
        if (q('[fullscreen]').length == 0) {
          document.body.style.overflow = 'auto';
        }
        n.querySelector('.head').style.cursor = 'move';
        n.querySelector('.fuller').children[0].className = 'fas fa-expand';
      } else {
        n.setAttribute('fullscreen', 'true');
        n.querySelector('.fuller').children[0].className = 'fas fa-compress';
        n.querySelector('.head').style.cursor = 'default';
        document.body.style.overflow = 'hidden';
      }
    } catch (e) {
    }
  }
  ui.win.minimize = function (n) {
    if (n.className == 'window') {
    }
    else
    {
      while (n.className != 'window') {
        n = n.parentElement;
      }
    }
    if (n.getAttribute('instack') == 'true') {
    } else {
      ui.win.stack.put(n);
    }
    n.setAttribute('minimized', 'true');
    n.style.display = 'none';
  }
  ui.win.stack = {
    show: function (wid) {
      if (this[wid]) {
        var w = this[wid];
        w.setAttribute('minimized', 'false');
        w.style.display = 'block';
      }
    },
    put: function (win) {
      var wdname = 'win' + randonInt(0, 1000);
      while (wdname in this) {
        wdname = 'win' + randonInt(0, 1000);
      }
      this[wdname] = win;
      win.setAttribute('instack', 'true');
      win.onclose = function () {
        this.setAttribute('minimized', 'true');
        this.style.display = 'none';
      }
      this.createButton(wdname);
    },
    createButton: function (wdname) {
      var btn = document.createElement('button');
      $(btn).attr({
        type: 'button',
        id: 'bs-' + wdname,
        onclick: 'ui.win.stack.show(\'' + wdname + '\')'
      });
      var btntext = this[wdname].tit();
      if (btntext.length > 8) {
        btntext = btntext.substr(0, 8) + '..';
      }
      btn.innerHTML = '<i class="fas fa-window-maximize"></i><br><span style="font-size:10px">' + btntext + '</span>';
      g('float-btn').insertBefore(btn, g('float-btn').children[0]);
    }
  }
  ui.win.create = function (title) {
    var win = document.createElement('div');
    win.className = 'window';
    win.innerHTML = '<div onclick="event.stopPropagation()"><div class="head" b><span class="headtext"></span><span class="closer" onclick="ui.win.close(this)"><i class="fas fa-times"></i></span><span class="fuller" onclick="ui.win.fullscreen(this)"><i class="fas fa-expand"></i></span></div><div class="body"></div></div>';
    win.body = function () {
      return win.querySelector('.body');
    }
    win.tit = function (nt) {
      if (nt)
      win.querySelector('.headtext').innerHTML = nt;
       else {
        return win.querySelector('.headtext').innerHTML;
      }
    }
    var header = win.querySelector('.head');
    win.movestate = {
      init: false,
      nd: win.children[0],
      resize: function () {
        var mvlr = event.pageX - this.crX;
        var mvud = event.pageY - this.crY;
        this.crX = event.pageX;
        this.crY = event.pageY;
        var movedx = this.left + mvlr;
        var movedy = this.top + mvud;
        this.nd.style.left = movedx;
        this.nd.style.top = movedy;
        this.left = movedx;
        this.top = movedy;
      }
    }
    header.addEventListener('mousedown', function () {
      var wd = ui.win.getWindow(this);
      var wdbox = wd.children[0];
      if (wd.hasAttribute('fullscreen')) {
        return;
      }
      if (wd.movestate.init == false) {
        wd.movestate.init = true;
        var bounding = wdbox.getBoundingClientRect();
        wd.movestate.left = bounding.x + bounding.width / 2;
        wd.movestate.top = bounding.y + bounding.height / 2;
        wdbox.style.left = wd.movestate.left;
        wdbox.style.top = wd.movestate.top;
      }
      wd.movestate.crX = event.pageX;
      wd.movestate.crY = event.pageY;
      var funmm = function () {
        wd.movestate.resize();
      }
      document.addEventListener('mousemove', funmm);
      var funmv = function () {
        document.removeEventListener('mousemove', funmm);
        document.removeEventListener('mouseup', funmv);
      }
      document.addEventListener('mouseup', funmv);
    });
    win.tit(title);
    win.onclose = function () {
      if (this.hasAttribute('fullscreen')) {
        if (q('[fullscreen]').length == 1) {
          document.body.style.overflow = 'auto';
        }
      }
      this.remove();
    }
    win.minimizable = function () {
      var sp = document.createElement('span');
      $(sp).attr({
        class : 'minimize',
        onclick: 'ui.win.minimize(this)'
      });
      sp.innerHTML = '<i class="far fa-window-minimize"></i>';
      win.querySelector('.head').appendChild(sp);
    }
    win.setAttribute('onclick', 'ui.win.close(this)');
    win.body.sec = function (text) {
      var sc = document.createElement('div');
      sc.setAttribute('sechead', '');
      sc.innerHTML = text;
      win.body().appendChild(sc);
      return sc;
    }
    win.body.row = function (text) {
      if (text != null) {
        win.body.sec(text);
      }
      var r = document.createElement('div');
      r.className = 'roww';
      win.body().appendChild(r);
      r.addPadder = function () {
        this.appendChild(win.padder());
      }
      r.addText = function (text) {
        r.innerHTML += '<span class=\'txt\'>' + text + '</span>';
      }
      r.addToggle = function (oncheckchange) {
        var tg = win.toggle(oncheckchange);
        r.addPadder();
        this.appendChild(tg);
        return tg;
      }
      r.addInput = function (id, placeholder) {
        var ip = win.input();
        ip.setAttribute('id', id);
        ip.setAttribute('placeholder', placeholder);
        this.appendChild(ip);
        return ip;
      }
      r.addButton = function (text, click, color, right) {
        var btn = win.button(text, click, color);
        if (right != null) {
          r.addPadder();
        }
        r.appendChild(btn);
        return btn;
      }
      return r;
    }
    win.checkbox = function () {
    }
    win.toggle = function (cb) {
      var tg = document.createElement('input');
      tg.setAttribute('type', 'checkbox');
      tg.setAttribute('right', '');
      tg.setAttribute('toggle', '');
      tg.onclick = cb;
      return tg;
    }
    win.padder = function () {
      var pd = document.createElement('div');
      pd.className = 'padder';
      return pd;
    }
    win.input = function () {
      var tg = document.createElement('input');
      tg.setAttribute('type', 'text');
      return tg;
    }
    win.label = function () {
    }
    win.data = function (key, value) {
      if (value != null) {
        win.key = value;
      } else {
        return key;
      }
    }
    win.button = function (text, onclick, color) {
      var btn = document.createElement('button');
      btn.setAttribute('onclick', onclick || '');
      btn.innerHTML = text;
      btn.className = color || '';
      return btn;
    }
    win.show = function () {
      document.body.appendChild(win);
    }
    win.hide = function () {
      win.remove();
    }
    win.addContent = function (str) {
      var token = str.split('|');
      var rw = win.body.row();
      if (token[0] == 'btn') {
        if (token[1]) {
        };
      }
    }
    return win;
  }
  ui.win.createFrame = function (title, link) {
    var wd = ui.win.create(title);
    var wdname = ui.createName();
    wd.showLink = function (reallink) {
      if (!reallink) {
        reallink = link;
      }
      var sp = document.createElement('span');
      sp.className = 'gotolink';
      sp.innerHTML = '<i class="fas fa-arrow-alt-to-right"></i>';
      sp.onclick = function () {
        window.location = reallink;
      }
      wd.querySelector('.head').appendChild(sp);
    }
    wd.body().innerHTML = '<iframe id=\'window-' + wdname + '\' src=\'' + link + '\' style=\'width:100%;height:100%;border:none;\'  target=\'_self\'></iframe>';
    return wd;
  }
  ui.win.createBox = function (title, link) {
    var wd = ui.win.create(title);
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function () {
      if (ajax.readyState == 4 && ajax.status == 200) {
        var response = ajax.responseText;
        wd.body().innerHTML = response;
        var arr = wd.body().getElementsByTagName('script');
        for (var n = 0; n < arr.length; n++) (function () {
          eval.apply(this, arguments);
        }(arr[n].innerHTML))
      }
    };
    ajax.open('GET', '/html/' + link + '.html', true);
    ajax.send();
    return wd;
  }
  ui.win.getWindow = function (e) {
    while (e.className != 'window') {
      e = e.parentElement;
    }
    return e;
  }
  function ip(id) {
    var e = g('ip-' + id);
    if (!e) {
      return g(id);
    }
    return e;
  }
  function val(id) {
    var e = ip(id);
    if (e.type == 'checkbox') {
      return ip(id).checked;
    }
    return ip(id).value || ip(id).checked || '';
  }
  function setval(id, val) {
    ip(id).value = val;
  }
  function modact(param, callb) {
    var http = new XMLHttpRequest();
    http.open('POST', '/background/action.php', true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
      if (http.readyState == 4 && http.status == 200) {
        if (callb) {
          callb(http.responseText);
        }
      }
    }
    http.send(param);
  }
  var pushserver = null;
  ui.style = {
  };
  ui.style.buildcss = function (obj) {
    var css = '';
    for (var style in obj) {
      css += style.replace(/([A-Z])/g, '-$1') + ':' + obj[style];
    }
    return css;
  }
  ui.style.parsecss = function (txt) {
    var obj = txt.split(';');
    var css = {
    };
    var _t;
    for (var i = 0; i < obj.length; i++) {
      _t = obj[i].trim();
      if (_t != '') {
        var express = _t.split(':');
        css[express[0].replace(/(\-[a-z])/g, function (a, b) {
          return b[1].toUpperCase()
        })] = _t[1] || '';
      }
    }
    return css;
  }
  ui.style.create = function (text) {
    var st = document.createElement('style');
    st.collection = {
    };
    st.build = function () {
      var css = [
      ];
      for (var id in this.collection) {
        css.push(id + '{' + this.collection[id].txt + '}');
      }
      this.innerHTML = css.join('');
      return this;
    };
    st.set = function (id, css) {
      if (typeof css == 'string') {
        this.collection[id] = {
          txt: css,
          css: ui.style.parsecss(css)
        };
      } else {
        this.collection[id] = {
          txt: ui.style.buildcss(css),
          css: css
        };
      }
      this.build();
      return this;
    };
    st.update = function (id, val) {
      var stl = this.collection[id];
      if (typeof css == 'string') {
        var newstl = ui.style.parsecss(css);
        for (var style in newstl) {
          stl.css[style] = newstl[style];
        }
        stl.txt = ui.style.buildcss(stl.css);
      } else {
        for (var style in val) {
          stl.css[style] = val[style];
        }
        stl.txt = ui.style.buildcss(stl.css);
      }
      this.build();
      return this;
    }
    st.use = function () {
      document.head.appendChild(st);
      return this;
    }
    st.hide = function () {
      document.head.removeChild(st);
      return this;
    }
    if (text != null) {
      st.innerHTML = text;
    }
    return st;
  }
  ui.scriptmanager = {
    stack: {
    }
  };
  ui.scriptmanager.load = function (scrurl, onload) {
    if (scrurl in this.stack) {
      return;
    } else {
      var sc = document.createElement('script');
      document.head.appendChild(sc);
      sc.onload = function () {
        if (onload) {
          onload();
        }
        this.remove();
      }
      sc.src = scrurl;
      this.stack[scrurl] = sc;
    }
  }
  var st = ui.style;
