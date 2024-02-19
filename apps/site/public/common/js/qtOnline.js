var contentcontainer = 'maincontent';
var abookchapter,
abookhost,
abookid;
var tse = {
  ws: {
  },
  connected: false,
  startconnect: false,
  autoexcute: false,
  connecting: false,
  connect: function () {
    if (this.startconnect) return;
    this.startconnect = true;
    if (window.endpoint) {
      try {
        if (location.protocol !== 'https:') {
          this.ws = new WebSocket('ws://giangthe.com' + window.endpoint);
          this.connecting = true;
        }
        else {
          this.ws = new WebSocket('wss://giangthe.com' + window.endpoint);
          this.connecting = true;
        }
      } catch (errr) {
        try {
          this.ws = new WebSocket('wss://giangthe.com' + window.endpoint);
          this.connecting = true;
        } catch (e) {
          this.ws = {
            send: function () {
              void (0);
            }
          }
        }
      }
    } else {
      this.ws.readyState = 2;
      this.connected = true;
    }
    this.ws.onopen = function () {
      tse.lastpacket = new Date().getTime();
      tse.connected = true;
      tse.connecting = false;
      if (tse.autoexcute) {
        excute(true);
        tse.autoexcute = false;
      }
      while (tse.waiting.length > 0) {
        var ppacket = tse.waiting.pop();
        this.send(ppacket);
      }
    }
    this.ws.onmessage = function (mes) {
      var id = parseInt(mes.data.substring(0, 8));
      tse.capture[id].down = mes.data.substring(8);
      try {
        tse.capture[id].callback(tse.capture[id].down);
      }
      catch (except) {
        if (true || window.debug) {
          console.log(except);
          console.log(tse.capture[id].callback.toString());
          console.log(tse.capture[id].up);
        }
      }
      tse.pending = tse.pending - 1;
      if (tse.pending == 0) {
        tse.onall();
      }
    }
    this.ws.onerror = function (event) {
      tse.connecting = false;
    }
  },
  lastpacket: 0,
  monitor: false,
  reconnect: function () {
    if (this.ws.readyState == 3) {
      this.connected = false;
      this.startconnect = false;
      this.connect();
    }
  },
  pad: function (n, width, z) {
    z = z || '0';
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
  },
  messageid: 1,
  pending: 0,
  waiting: [
  ],
  send: function (type, data, callback) {
    if (this.ws.readyState == 3) {
      this.reconnect();
    }
    if (type == '001') {
      if (data == '|' || data == '') return;
      var mea = phrasetree.getmean(data).split('=');
      if (mea.length > 1) {
        var pk = {
        };
        pk.down = mea[1];
        pk.callback = callback;
        pk.callback(mea[1]);
        return;
      } else if (!window.endpoint) {
        this.formXhr(data, function (d) {
          var pk = {
          };
          pk.down = d;
          pk.callback = callback;
          pk.callback(d);
        });
        return;
      }
    }
    if (type == '007') {
      if (data == '|' || data == '') return;
      var mea = phrasetree.getmean(data);
      if (mea != '') {
        var pk = {
        };
        pk.down = mea;
        pk.callback = callback;
        try {
          pk.callback(mea);
        } catch (xx) {
        }
        return;
      }
      else if (window.endpoint) {
        type = '004';
      } else {
        this.formXhr(data, function (d) {
          var cmb1 = data.split('|');
          var cmb2 = d.split('|');
          var cmbl = [
          ];
          for (var k = 0; k < cmb1.length; k++) {
            cmbl.push(convertohanviets(cmb1[k]) + '=' + cmb2[k]);
          }
          var pk = {
          };
          pk.down = cmbl.join('|');
          pk.callback = callback;
          try {
            pk.callback(pk.down);
          } catch (xx) {
          }
        });
        return;
      }
    }
    if (type == '005') {
      if (data == '') return;
       else if (!window.endpoint) {
        this.formXhr2(data, function (d) {
          var pk = {
          };
          pk.down = d;
          pk.callback = callback;
          try {
            pk.callback(pk.down);
          } catch (xx) {
          }
        });
        return;
      }
    }
    if (type == '002' && (data == '|' || data == '')) {
      var pk = {
      };
      pk.down = '=';
      pk.callback = callback;
      pk.callback('=');
      return;
    }
    if (type == '004') {
      var mn = phrasetree.getmean(data);
      if (mn) {
        var pk = {
        };
        if (mn[0] == '=')
        pk.down = convertohanviets(data) + mn;
         else {
          pk.down = mn;
        }
        pk.callback = callback;
        try {
          pk.callback(pk.down);
        } catch (xx) {
        }
        return;
      }
    }
    var pk = {
    };
    pk.id = this.messageid;
    var pa = this.pad(pk.id, 8);
    pk.type = type;
    pk.up = data;
    pk.callback = callback || pk.callback;
    this.capture[pk.id] = pk;
    try {
      if (!this.ws.readyState == WebSocket.OPEN && window.endpoint) {
        this.waiting.push(pa + pk.type + data);
      }
      else if (window.endpoint) {
        this.ws.send(pa + pk.type + data);
        this.lastpacket = new Date().getTime();
      } else {
      }
    } catch (e) {
      console.log(e);
    }
    this.messageid++;
    this.pending++;
  },
  onall: function () {
  },
  capture: {
  },
  xhrPending: false,
  xhrPending2: false,
  formXhr: function (msg, calb) {
    if (this.xhrPending == false) {
      var pendingMsg = {
      };
      pendingMsg.msg = [
      ];
      pendingMsg.pending = [
      ];
      pendingMsg.timer = setTimeout(function () {
        pendingMsg.send();
      }, 1000);
      pendingMsg.send = function () {
        window.tse.xhrPending = false;
        var reference = this;
        ajax('ajax=trans&content=' + encodeURIComponent(this.msg.join('<split>')), function (down) {
          var rspList = down.toLowerCase().split('<split>');
          for (var i = 0; i < rspList.length; i++) {
            var packet = {
              down: rspList[i].trim(),
              callback: reference.pending[i]
            }
            packet.callback(packet.down);
          }
          replaceName();
        });
      }
      pendingMsg.add = function (msg, calb) {
        this.pending.push(calb);
        this.msg.push(msg);
      }
      this.xhrPending = pendingMsg;
    }
    this.xhrPending.add(msg, calb);
  },
  formXhr2: function (msg, calb) {
    if (this.xhrPending2 == false) {
      var pendingMsg = {
      };
      pendingMsg.msg = [
      ];
      pendingMsg.pending = [
      ];
      pendingMsg.timer = setTimeout(function () {
        pendingMsg.send();
      }, 1000);
      pendingMsg.send = function () {
        window.tse.xhrPending2 = false;
        var reference = this;
        ajax('ajax=worddict&content=' + encodeURIComponent(this.msg.join('<split>')), function (down) {
          var rspList = down.substring(1).toLowerCase().split('<split>');
          for (var i = 0; i < rspList.length; i++) {
            var packet = {
              down: rspList[i].trim(),
              callback: reference.pending[i]
            }
            packet.callback(packet.down);
          }
        });
      }
      pendingMsg.add = function (msg, calb) {
        this.pending.push(calb);
        this.msg.push(msg);
      }
      this.xhrPending2 = pendingMsg;
    }
    this.xhrPending2.add(msg, calb);
  }
};
var callb = [
];
var store = localStorage;
var calfunc = {
  func: function (e) {
  },
  excute: function (e) {
    func(e);
    isrunned = true;
  },
  isrunned: false
}
if (window.NodeList && !NodeList.prototype.forEach) {
  NodeList.prototype.forEach = function (callback, thisArg) {
    thisArg = thisArg || window;
    for (var i = 0; i < this.length; i++) {
      callback.call(thisArg, this[i], i, this);
    }
  };
}
function findsel() {
  if (window.isMobile) {
    return;
  }
  var sel = getSelectionText();
  if (sel != '') {
    g('fastseltext').value = sel;
    g('fastgentext').value = titleCase(sel);
  }
}
function bigsel() {
}
function arrtoobj(arr) {
  var obj = {
  };
  for (var i = 0; i < arr.length; i++) {
    obj[arr[i]] = true;
  }
  obj.indexOf = function (find) {
    if (find in this) return 1;
    return - 1;
  }
  obj.have = function (find) {
    return find in this;
  }
  return obj;
}
function arrstoobj(arrs) {
  var obj = {
  };
  var l;
  for (var c = 0; c < arrs.length; c++) {
    l = arrs[c].length;
    for (var i = 0; i < l; i++) {
      obj[arrs[c][i]] = true;
    }
  }
  obj.indexOf = function (find) {
    if (find in this) return 1;
    return - 1;
  }
  obj.have = function (find) {
    return find in this;
  }
  return obj;
}
function fastAddNS() {
  var left = g('fastseltext');
  var right = g('fastgentext');
  if (left != '') {
    namew.value += '\n' + left.value + '=' + right.value;
  }
  saveNS();
}
var runned = false;
function joinfromto(arr, st, en) {
  var str = '';
  for (var i = st; i <= en; i++) {
    str += arr[i];
  }
  return str;
}
Array.prototype.joinlast = function (last) {
  for (var i = 0; i < last; i++) this.shift();
  return this.join('=');
};
function flushToView() {
  var tmpct = g(contentcontainer);
  g('tmpcontentview').innerHTML = tmpct.innerHTML.replace(/ ,/g, ',').replace(/<br>/g, '<p>').replace(/<br \/>/g, '</p>').replace(/ \./g, '.');
  g('tmpcontentview').id = contentcontainer;
  tmpct.id = 'tmpcontentdiv';
}
function pushFromView() {
  var tmpct = g('tmpcontentdiv');
  tmpct.innerHTML = g(contentcontainer).innerHTML.replace(/<p>/g, '<br>').replace(/<\/p>/g, '<br />');
  g(contentcontainer).id = 'tmpcontentview';
  tmpct.id = contentcontainer;
}
function excute(invokeMeanSelector) {
  if (g(contentcontainer) == null) return;
  if (typeof (thispage) == 'undefined') return;
  if (!defined) return;
  if (dictionary.finished == false) {
    dictionary.readTextFile('//giangthe.com/wordNoChi.htm?update=1');
    phrasetree.load();
    tse.connect();
    return;
  }
  if (tse.ws.readyState != 1) {
    tse.autoexcute = true;
    tse.connect();
  }
  var curl = document.getElementById('hiddenid').innerHTML.split(';');
  var book = curl[0];
  var chapter = curl[1];
  var host = curl[2];
  hideNb();
  replaceVietphrase();
  if (host != 'dich')
  fastNaming();
  if (window.endpoint) {
    prediction.enable = true;
  }
  var reg;
  if (window.setting != null && window.setting.onlyonename) {
    reg = store.getItem('qtOnline0');
  } else
  reg = store.getItem(host + book);
  if (reg == null) {
    reg = store.getItem(book);
    if (reg != null) {
      store.setItem(host + book, reg);
    }
  }
  if (reg != null) {
    var rowlist = reg.split('~//~');
    if (window.namew == null) {
      window.namew = g('namewd');
    }
    if (namew == null) {
      setTimeout(function () {
        excute();
      }, 500);
      return;
    }
    namew.value = rowlist.join('\n');
    if (window.bookHaveDefaultName) {
      var dfname = window.bookDefaultName.split('\n');
      dfname.forEach(function (e) {
        if (e != '') {
          var row = e.split('=');
          if (row.length < 2) {
          }
          else {
            if (row[0] != '') {
              if (row[0].charAt(0) == '@') {
                row[0] = row[0].substring(1).split('|');
                if (row[1] != null)
                row[1] = row[1].split('|');
                replaceByNode(row[0], row[1]);
              } else
              if (row[0].charAt(0) == '#') {
                dictionary.set(row[0].substring(1), row[1]);
              } else
              if (row[0].charAt(0) == '$') {
                var sear = row[0].substring(1);
                var rep = row.joinlast(1);
                if (sear.length == 1) {
                  if (convertohanviets(sear) == rep.toLowerCase()) {
                    return;
                  }
                }
                if (true) {
                  dictionary.set(sear, rep);
                  nametree.setmean(sear, '=' + rep);
                } else
                replaceOnline(sear, rep);
              } else
              if (row[0].charAt(0) == '~') {
                meanengine(e.substr(1));
              }
              else {
                toeval2 += 'replaceByRegex("' + eE(row[0]) + '","' + eE(row[1]) + '");';
              }
            }
          }
        }
      });
    }
    rowlist.forEach(function (e) {
      if (e != '') {
        var row = e.split('=');
        if (row.length < 2) {
        }
        else {
          if (row[0] != '') {
            if (row[0].charAt(0) == '@') {
              row[0] = row[0].substring(1).split('|');
              if (row[1] != null)
              row[1] = row[1].split('|');
              replaceByNode(row[0], row[1]);
            } else
            if (row[0].charAt(0) == '#') {
              dictionary.set(row[0].substring(1), row[1]);
            } else
            if (row[0].charAt(0) == '$') {
              var sear = row[0].substring(1);
              var rep = row.joinlast(1);
              if (sear.length == 1) {
                if (convertohanviets(sear) == rep.toLowerCase()) {
                  return;
                }
              }
              if (window.setting && window.setting.allownamev3) {
                dictionary.set(sear, rep);
                nametree.setmean(sear, '=' + rep);
              } else
              replaceOnline(sear, rep);
            } else
            if (row[0].charAt(0) == '~') {
              meanengine(e.substr(1));
            }
            else {
              toeval2 += 'replaceByRegex("' + eE(row[0]) + '","' + eE(row[1]) + '");';
            }
          }
        }
      }
    });
  }
  if (window.setting && window.setting.allownamev3) {
    replaceName();
  }
  needbreak = false;
  meanengine.usedefault();
  if (!tse.connecting) {
    if (invokeMeanSelector == null || invokeMeanSelector !== false) {
      window.meanSelectorCheckpoint = 0;
      if (window.lazyProcessor) {
        window.lazyProcessor.clear();
      }
      meanSelector();
      setTimeout(function () {
      }, 1200);
    }
  }
  setTimeout(doeval, 100);
  runned = true;
}
function autocheck() {
  if (!runned) excute();
}
function sortname() {
  var str = namew.value.split('\n').sort();
  for (var i = 9999; i < str.length; i++) {
    if (str[i].charAt(0) == '$') {
      if (str[i + 1] != null && (str[i + 1].substring(0, str[i].split('=') [0].length) == str[i].split('=') [0])) {
        if (str[i + 1].length = str[i].length + 1) {
          if (str[i + 1].lastChar() != '?') {
            str[i] = '';
          }
        }
      }
    }
  }
  for (var i = 0; i < str.length; i++) {
    if (str[i + 1] === str[i]) {
      str[i + 1] = '';
    }
  }
  str = str.sort(function (a, b) {
    if (a.charAt(0) == '#') return - 1;
     else return a.split('=') [0].length - b.split('=') [0].length;
  });
  var lastans = '';
  for (var i = 0; i < str.length; i++) {
    if (str[i] != '') {
      lastans += str[i] + '\n';
    }
  }
  namew.value = lastans;
  saveNS();
}
function ensure(node, id) {
  var nodelist = g(contentcontainer).childNodes;
  var exranid = 0;
  nodelist.forEach(function (e) {
    if (e.nodeType == 3) {
      if (e.textContent.match(/[^ \.,“\:\?”\!\"\*\)\(\$\^\+\@\%\|\/\=\?????…«»‘’\r\n]/)) {
        converttonode(e, id + 'r' + exranid);
        exranid++;
      }
    }
    if (e.tagName == 'p') {
      ensure(e, id + 'r' + id);
    }
  });
}
function fastNaming() {
  if (g(contentcontainer) == null) return;
  var nodelist = g(contentcontainer).childNodes;
  var exranid = 0;
  nodelist.forEach(function (e) {
    if (e.nodeType == 3) {
      if (e.textContent === ' ') {
        e.isspacehidden = true;
        return;
      }
      if (e.textContent.match(/[^ \.,“\:\?”\!\"\*\)\(\$\^\+\@\%\|\/\=\?????…«»‘’\r\n\u200B]/)) {
        converttonode(e, 'exran' + exranid);
        exranid++;
      }
      e.isexran = true;
    }
    if (e.tagName == 'p') {
      ensure(e, exranid);
    }
    if (e.nodeType == 8) {
      e.remove();
    }
  });
  var keyword1 = [
    '«',
    '?',
    '?',
    '<',
    '?',
    '[',
    '‘',
    '“'
  ];
  keyword1.forEach(function (e) {
  });
  var keyword2 = [
    '?',
    ']',
    '?',
    '?',
    '»',
    '>',
    '’',
    '”'
  ];
  keyword2.forEach(function (e) {
  });
  q('.fastname').forEach(function (e) {
    if (e.innerText.length > 60) e.style.textTransform = '';
  });
}
function instring(str1, str2) {
  for (var i = 0; i < str2.length; i++) {
    if (str1.indexOf(str2.charAt(i)) >= 0) return true;
  }
  return false;
}
Element.prototype.containName = function () {
  return this.isname() || ((titleCase(this.textContent) == this.textContent) && this.textContent.indexOf(' ') > 0);
};
Element.prototype.containHan = function (callback, none, nofast) {
  var t = this.gT();
  if (instring(t, meanstrategy.ignore)) {
    if (this.pE())
    if (!instring(t, meanstrategy.ignore2) || meanstrategy.testcommon([this.pE(),
    this]) < 2) {
      return;
    }
  }
  if (this.isname()) return;
  if (!nofast && (this.textContent == this.gH() || this.containName())) {
    callback();
    return;
  }
  if (t in meanstrategy.database) {
    if (meanstrategy.database[t].toLowerCase().indexOf(this.gH()) >= 0) {
      callback(meanstrategy.database[t]);
    } else if (none != null) {
      none();
    }
    return;
  }
  var _self = this;
  var mean = phrasetree.getmean(t);
  if (mean != '') {
    mean = mean.split('=') [1];
    if (mean != null) {
      if (mean.toLowerCase().indexOf(this.gH()) >= 0) {
        callback(mean);
      } else if (none != null) {
        none();
      }
    }
  } else if (this.mean()) {
    if (this.mean().toLowerCase().indexOf(this.gH()) >= 0) {
      callback(this.mean());
    } else if (none != null) {
      none();
    }
  }
  else
  tse.send('001', t, function () {
    meanstrategy.database[this.up] = this.down;
    if (_self.isname()) return;
    if (this.down.toLowerCase().indexOf(_self.gH()) >= 0) {
      callback(this.down);
    } else if (none != null) {
      none();
    }
  });
};
Element.prototype.mean = function () {
  return this.getAttribute('v');
}
Element.prototype.near = function (end) {
  if (end) {
    var walked = 0;
    var nod = this;
    for (var i = 0; i < 3; i++) {
      if (nod.nextSibling != null) {
        walked += nod.gT().length;
        if (walked > 7) return false;
        if (/[\.,]/.test(nod.nextSibling.textContent)) {
          return true;
        }
        nod = nod.nextElementSibling;
        if (nod.tagName == 'BR') return true;
      } else return true;
    }
    return false;
  } else {
    var walked = 0;
    var nod = this;
    for (var i = 0; i < 3; i++) {
      if (nod.previousSibling != null) {
        walked += nod.gT().length;
        if (walked > 7) return false;
        if (/[\.,]/.test(nod.previousSibling.textContent)) {
          return true;
        }
        nod = nod.previousElementSibling;
        if (nod.tagName == 'BR') return true;
      } else return true;
    }
    return false;
  }
}
Element.prototype.pE = function () {
  return this.previousElementSibling;
};
Element.prototype.nE = function () {
  return this.nextElementSibling;
};
Element.prototype.gT = function () {
  return this.cn || this.getAttribute('t') || '';
};
Element.prototype.gH = function () {
  return this.getAttribute('h') || '';
};
Element.prototype.isname = function () {
  return this.getAttribute('isname') === 'true';
};
Element.prototype.tomean = function (mean) {
  if (mean == '') {
    this.textContent = '';
    return;
  }
  if (!isUppercase(this)) {
    this.textContent = mean;
  } else {
    this.textContent = mean[0].toUpperCase() + mean.substring(1);
  }
};
Element.prototype.getmean = function (callb) {
  if (this.gT() in meanstrategy.database) {
    callb(meanstrategy.database[this.gT()]);
  } else {
    tse.send('001', this.gT(), function () {
      meanstrategy.database[this.up] = this.down.trim();
      callb(this.down.trim());
    });
  }
};
Element.prototype.isspace = function (right) {
  if (right) return this.nextSibling != null && (this.nextSibling.textContent === ' ' || this.nextSibling.isspacehidden);
   else {
    return this.previousSibling != null && (this.previousSibling.textContent === ' ' || this.previousSibling.isspacehidden);
  }
};
String.prototype.splitn = function (n) {
  var arr = [
  ];
  var str = '';
  var chars = 0;
  for (var i = 0; i < this.length; i++) {
    str += this.charAt(i);
    chars++;
    if (n == chars) {
      arr.push(str);
      str = '';
      chars = 0;
    }
  }
  if (str != '') arr.push(str);
  return arr;
}
var looper = {
  search: function (node, right, max, find, senonly) {
    if (right) {
      for (var i = 0; i < max; i++) {
        node = node.nE();
        if (node == null) return false;
        if (senonly && !node.isspace(false)) return false;
        if (node.gT() [0] == find) {
          return node;
        }
      }
    } else {
      for (var i = 0; i < max; i++) {
        node = node.pE();
        if (node == null) return false;
        if (senonly && !node.isspace(true)) return false;
        if (node.gT().lastChar() == find) {
          return node;
        }
      }
    }
    return false;
  },
  searchphrase: function (node, right, max, find, senonly) {
    if (right) {
      for (var i = 0; i < max; i++) {
        node = node.nE();
        if (node == null) return false;
        if (senonly && !node.isspace(false)) return false;
        if (node.gT().indexOf(find) == 0) {
          return node;
        }
      }
    } else {
      for (var i = 0; i < max; i++) {
        node = node.pE();
        if (node == null) return false;
        if (senonly && !node.isspace(true)) return false;
        if (node.gT().endwith(find)) {
          return node;
        }
      }
    }
    return false;
  },
  searchphraseex: function (node, right, max, find, senonly) {
    if (right) {
      for (var i = 0; i < max; i++) {
        node = node.nE();
        if (node == null) return false;
        if (senonly && !node.isspace(false)) return false;
        for (var j = 0; j < find.length; j++)
        if (node.gT().indexOf(find[j]) == 0) {
          return node;
        }
      }
    } else {
      for (var i = 0; i < max; i++) {
        node = node.pE();
        if (node == null) return false;
        if (senonly && !node.isspace(true)) return false;
        for (var j = 0; j < find.length; j++)
        if (node.gT().endwith(find[j])) {
          return node;
        }
      }
    }
    return false;
  },
  searchex: function (node, right, max, find, senonly) {
    if (right) {
      for (var i = 0; i < max; i++) {
        node = node.nE();
        if (node == null) return false;
        if (senonly && !node.isspace(false)) return false;
        if (find.indexOf(node.gT() [0]) > - 1) {
          return node;
        }
      }
    } else {
      for (var i = 0; i < max; i++) {
        node = node.pE();
        if (node == null) return false;
        if (senonly && !node.isspace(true)) return false;
        if (find.indexOf(node.gT().lastChar()) > - 1) {
          return node;
        }
      }
    }
    return false;
  }
}
function pIsNewLine(node) {
  if (node.pE()) {
    return node.pE().tagName == 'BR';
  } else return true;
}
function isUppercase(node) {
  if (node.push) {
    node = node[0];
  }
  if (node.pE()) {
    if (node.pE().tagName == 'BR') {
      return true;
    } else return /[«:\.“]/.test(node.previousSibling.textContent);
  } else return true;
}
function ucFirst(t) {
  if (t.length > 0)
  return t[0].toUpperCase() + t.substring(1);
  return '';
}
function getDefaultMean(node) {
  if (!node.getAttribute) {
    return node.textContent;
  }
  var m = node.getAttribute('v');
  if (typeof m != 'undefined' && m != null) {
    return m.split('/') [0] || '';
  } else if (m == null) {
    console.log(node);
  }
  return node.gH();
}
function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}
var meanstrategy = {
  'collected': '',
  'nodelist': {
  },
  'highlight': function (node, type) {
    try {
      if (setting.highlight === true) {
        switch (type) {
          case 'f':
            node.style.backgroundColor = '#78aafa';
            break;
          case 'm':
            node.style.backgroundColor = '#eeeeee';
            break;
          case 'o':
            node.style.backgroundColor = '#38ff38';
            break;
          case 's':
            node.style.backgroundColor = '#8fceab';
            break;
          case 'e':
            node.style.backgroundColor = '#d866ff';
            break;
          case 'ln':
            node.style.backgroundColor = '#bae7b4';
          default:
            break;
        }
      }
    } catch (xxx) {
    }
  },
  'countname': function (right, start, max) {
    if (!right) {
      var pnum = 0;
      var count = 0;
      var curnod = start;
      while (pnum < max && curnod.previousElementSibling != null) {
        pnum++;
        curnod = curnod.previousElementSibling;
        if (curnod.containName()) {
          count++;
        }
      }
      return count;
    } else {
      var pnum = 0;
      var count = 0;
      var curnod = start;
      while (pnum < max && curnod.nextElementSibling != null) {
        pnum++;
        curnod = curnod.nextElementSibling;
        if (curnod.containName()) {
          count++;
        }
      }
      return count;
    }
  },
  scope: function (node, type) {
    if (!setting.scopefilter) {
      return;
    }
    var curnod = node.nextSibling;
    var nodlist = [
    ];
    var walked = 0;
    var flag = false;
    var tester = this.database.scope.close.charAt(this.database.scope.open.indexOf(type));
    var breaker = /[.;:,?!]/;
    var looped = 0;
    while (curnod != null) {
      looped++;
      if (looped > 10) return;
      if (curnod.nodeType == Element.TEXT_NODE) {
        continue;
      }
      walked += curnod.gT().length;
      if (walked > 6) return;
      nodlist.push(curnod);
      if (curnod.nextSibling != null) {
        if (curnod.nextSibling.textContent.indexOf(tester) > - 1) {
          flag = 1;
          break;
        }
        if (breaker.test(curnod.nextSibling.textContent)) {
          return;
        }
      }
      curnod = curnod.nE();
      if (curnod == null || curnod.tagName == 'BR') {
        return;
      }
    }
    if (flag) {
      nodlist.forEach(function (e) {
        e.containHan(function () {
          e.textContent = meanstrategy.testsuffix(e.gT(), titleCase(e.gH()));
        });
      });
      if (walked < 6) {
        if (type != '‘')
        analyzer.update(nodlist.sumChinese(), 3);
      }
    }
  },
  worddelay: function (node) {
    if (node.previousSibling && node.previousSibling.tagName == 'I' && node.previousSibling.gT().length == 1) {
      if (node.nextSibling && node.nextSibling.tagName == 'I' && node.nextSibling.gT().charAt(0) == node.previousSibling.gT() && !this.database.pronoun.contain(node.previousSibling.gT())) {
        var neww = node.nextSibling.innerHTML.split(' ') [0];
        if (node.previousSibling.gT() in this.database.preposition) {
          neww = titleCase(this.database.preposition[node.previousSibling.gT()]);
        }
        node.previousSibling.textContent = neww;
        if (node.nextSibling.gT().length == 1) {
        }
        this.highlight(node, 'm');
      }
    }
  },
  meancomparer: function (arr1, arr2, allowback) {
    for (var i = 0; i < arr1.length; i++) {
      var tx = arr1[i].split(' ');
      var lastword = tx.pop();
      for (var j = 0; j < arr2.length; j++) {
        if (lastword == arr2[j].split(' ') [0]) {
          tx = tx.join(' ').trim() + ' ' + arr2[j].trim();
          if (allowback) {
            tx = tx.replace('giá', 'cái này').replace('nang', 'có th?');
          }
          return {
            code: true,
            new : tx
          };
        }
      }
    }
    return {
      code: false
    };
  },
  wordconnector: function (node) {
    if (this.connectignore.indexOf(node.gT()) > - 1) return;
    if (node.isspace(false)) {
      if (node.pE() != null) {
        if (node.pE().isname()) return;
        if (node.pE().gT().length > 3 || node.pE().gT() < 2) return;
        node.pE().getmean(function (mean1) {
          var phrase = [
            node.pE(),
            node
          ].sumChinese('');
          var chi2 = phrase.substring(node.pE().gT().length - 1);
          meanstrategy.database.getmean(chi2, function (mean2) {
            if (mean2 == 'false') return;
            mean1 = mean1.split('/');
            mean2 = mean2.split('/');
            var ret = {
              code: false
            };
            if (/[??]/.test(node.pE().gT())) {
              ret = meanstrategy.meancomparer([node.pE().gH()], mean2, true);
              if (ret.code) {
                node.pE().innerHTML = ret.new;
                node.pE().setAttribute('t', phrase);
                node.pE().cn = phrase;
                node.pE().setAttribute('h', node.pE().gH() + ' ' + node.gH());
                meanstrategy.highlight(node.pE(), 'm');
                node.innerHTML = '';
                node.setAttribute('h', '');
                node.setAttribute('t', '');
                node.cn = '';
                node.remove();
              }
            }
            if (!ret.code) {
              ret = meanstrategy.meancomparer(mean1, mean2);
              if (ret.code) {
                node.pE().innerHTML = ret.new;
                node.pE().setAttribute('t', phrase);
                node.pE().cn = phrase;
                node.pE().setAttribute('h', node.pE().gH() + ' ' + node.gH());
                meanstrategy.highlight(node.pE(), 'm');
                node.innerHTML = '';
                node.setAttribute('h', '');
                node.setAttribute('t', '');
                node.cn = '';
                node.remove();
              }
            }
          });
        });
      }
    }
  },
  loctransform: function (node, mean1, mean2) {
    return;
    if (node.innerHTML != '' && !node.innerHTML.contain(mean2)) return;
    var nd = looper.searchex(node, false, 3, '???', true);
    if (nd) {
      var nmean = '';
      var mean3 = mean2;
      if (nd.nE().gT() == '?') {
        nmean = 'này';
        nd.nE().innerHTML = '';
      }
      if (nd.nE().gT() == '??') {
        nmean = 'này';
        nd.nE().innerHTML = '';
        mean2 += ' cái';
      }
      if (node.gT().length == 1) {
        nd.innerHTML += ' ' + mean2;
        if (nmean != '') {
          node.innerHTML = nmean;
        } else
        node.innerHTML = node.innerHTML.replace(mean1, '').replace(mean2, '');
      }
      else {
        var m = getDefaultMean(nd) + ' ' + getDefaultMean(node);
        if (pIsNewLine(nd)) {
          m = capitalizeFirstLetter(m);
        }
        nd.innerHTML = m;
        this.highlight(nd, 'm');
        node.innerHTML = nmean;
      }
      return true;
    }
    return false;
  },
  '??': function (node) {
    if (!node.nE() || (node.nE().tagName == 'BR') || !node.isspace(true)) {
      node.innerHTML = '??';
    }
  },
  '_L': function (node) {
  },
  tokensregex: function (node, least, alter, end) {
    if (!setting.tokensregex) return;
    least = least || 2;
    end = end || '?';
    var cnl = node.cn.length;
    var nl = [
      node
    ];
    for (var i = 0; i < 5; i++) {
      node = node.nE();
      if (!node || !node.isspace(false) || node.cn.contain('?')) {
        break;
      }
      nl.push(node);
      cnl += node.cn.length;
    }
    for (; nl.length > 0; ) {
      if (this.testcommon(nl) >= least) {
        if (alter) {
          alter(nl);
        } else {
          for (var i = 0; i < nl.length; i++) {
            nl[i].textContent = titleCase(nl[i].textContent);
          }
          this.addname(3, nl.sumChinese(''), nl.sumHan(), 'titleCase');
        }
        return;
      }
      nl.pop();
    }
  },
  '??': function (node) {
    if (node.nE())
    if (node.nE().textContent[0].toLowerCase() == node.nE().textContent[0]) {
      this.tokensregex(node.nE(), 1);
    }
  },
  '??': function (node) {
    if (node.isspace(true)) {
      node.tomean('l?i nói');
    } else if (node.nextSibling && !(node.nextSibling.textContent.contain(','))) {
      node.tomean('l?i nói');
    }
  },
  '?': function (node) {
    if (node.pE() && node.pE().gT().lastChar() == '?') {
      if (node.nextSibling && node.nextSibling.textContent.contain(',')) {
        node.tomean('mà nói');
      }
    } else if (looper.searchphrase(node, false, 10, '??')) {
      node.tomean('mà nói');
    }
  },
  '?': function (node) {
    if (node.pE()) {
      if (node.pE().gT().lastChar() == '?') {
        if (node.pE().pE() && node.pE().pE().isname()) {
          swapnode(node, node.pE().pE());
        }
      }
    }
  },
  '???': function (node) {
    if (!node.isspace(true)) {
      node.tomean('tr? cho ta');
    } else
    prediction.parse(node, function (n, p, l, i) {
      var confident = 0;
      for (i = i + 1; i < l.length; i++) {
        if (l[i].tag == 'uj' || l[i].tag == 'n') {
          confident++;
        }
      }
      if (confident == 0) {
        n.tomean('còn cho ta');
      }
    }, '??');
  },
  '??': function (node) {
    if (node.pE() && node.pE().gT().lastChar() == '?') {
      node.tomean('tung tích');
    }
  },
  '?': function (node) {
    if (!node.isspace(false)) {
      node.tomean('nhung');
    }
  },
  '?': function (node) {
    if (!node.isspace(false)) {
      node.tomean('n?u');
    }
  },
  '??': function (node) {
    if (!node.isspace(false)) {
      node.tomean('làm gì');
    }
  },
  '?': function (node) {
    prediction.parse(node, function (n, p, d, i) {
      if (p == 'ud' && !d[i + 1] == 'v') {
        n.tomean('du?c');
      }
    });
  },
  '?': function (node) {
    this.loctransform(node, 'bên trên', 'trên');
    return;
    if (!node.innerHTML.contain('trên')) return;
    var nd = looper.searchex(node, false, 4, '???', true);
    if (nd) {
      if (node.gT().length == 1) {
        nd.innerHTML += ' trên';
        node.innerHTML = node.innerHTML.replace('bên trên', '').replace('trên', '');
      }
      else {
        nd.innerHTML += ' ' + node.innerHTML;
        node.innerHTML = '';
      }
    }
  },
  '??': function (node) {
    this.loctransform(node, 'bên trong', 'trong');
  },
  '??': function (node) {
    if (!node.isspace(true)) {
      var nd = looper.search(node, false, 10, '?', false);
      if (nd) {
        nd.tomean(getDefaultMean(nd) + ' tình c?nh');
        node.innerHTML = '';
      }
    }
  },
  '?': function (node) {
    if (!node.isspace(false) && looper.searchex(node, true, 7, '???', false)) {
      node.tomean('ch?');
    }
  },
  '-?': function (node) {
    this.loctransform(node, 'phía du?i', 'du?i');
    return;
    if (!node.innerHTML.contain('du?i')) return;
    var nd = looper.searchex(node, false, 4, '???', true);
    if (nd) {
      if (node.gT().length == 1) {
        nd.innerHTML += ' du?i';
        node.innerHTML = node.innerHTML.replace('phía du?i', '').replace('du?i', '');
      }
      else {
        nd.innerHTML += ' ' + node.innerHTML;
        node.innerHTML = '';
      }
    }
  },
  '?': function (node) {
    if (!node.isspace(true) && node.pE() && node.pE().gT().lastChar() == '?') {
      node.tomean('vô cùng');
    }
  },
  '?': function (node) {
    if (!node.isspace(true) && node.pE() && node.pE().gT().lastChar() == '?') {
      node.tomean('vô cùng');
    }
  },
  '??': function (node) {
    if (!node.isspace(true)) {
      node.tomean('vô cùng');
    }
  },
  '??': function (node) {
    if (node.isspace(false)) {
      node.tomean('l?i là');
    }
  },
  '??': function (node) {
    if (!node.isspace(true)) {
      node.tomean('v?n d? gì');
    }
  },
  '?': function (node) {
    if (node.pE() && node.isspace(false) && node.pE().gT().lastChar() == '?') {
      node.tomean('');
    }
  },
  '??': function (node) {
    if (node.nextSibling.textContent.contain('?')) {
      node.tomean('hay sao');
    }
  },
  '??': function (node) {
    this.loctransform(node, 'bên trong', 'trong');
    return;
    if (!node.innerHTML.contain('trong')) return;
    var nd = looper.searchex(node, false, 4, '???', true);
    if (nd) {
      if (node.gT().length == 1) {
        nd.innerHTML += ' trong';
        node.innerHTML = node.innerHTML.replace('bên trong', '').replace('trong', '');
      }
      else {
        nd.innerHTML += ' ' + node.innerHTML;
        node.innerHTML = '';
      }
    }
  },
  '?': function (node) {
    if (node.nE() && this.containnumber(node.nE())) {
      node.tomean('vu?t');
    }
  },
  '??': function (node) {
    if (!node.isspace(true)) {
      node.innerHTML = 'dáp l?i';
    }
  },
  '??': function (node) {
    if (node.pE() && this.containnumber(node.pE())) {
      if (node.pE() && node.pE().pE() && (node.pE().pE().isspace(true) || node.pE().innerHTML[0] == ' ')) {
        node.pE().pE().tomean(getDefaultMean(node.pE().pE()) + ' trên du?i');
        node.innerHTML = '';
      } else {
        if (node.pE().isspace(false)) {
          node.pE().tomean('trên du?i ' + getDefaultMean(node.pE()));
          node.innerHTML = '';
        } else if (node.pE().innerHTML[0] == ' ') {
          node.pE().tomean(' trên du?i' + getDefaultMean(node.pE()));
          node.innerHTML = '';
        } else {
          node.pE().tomean(' trên du?i ' + getDefaultMean(node.pE()));
          node.innerHTML = '';
        }
      }
    }
  },
  '???': function (node) {
    this['??'](node);
  },
  '?': function (node) {
    if (!node.isspace(true)) {
      node.tomean('l?nh');
      meanstrategy.highlight(node, 'm');
    }
    if (node.pE() && node.pE().tagName == 'I') {
      if (node.pE().innerHTML.toLowerCase() == node.pE().gH()) {
        node.tomean('l?nh');
        meanstrategy.highlight(node, 'm');
      }
    };
  },
  '??': function (node) {
    if (node.pE() && node.pE().tagName == 'I') {
      if (node.pE().innerHTML.toLowerCase() == node.pE().gH()) {
        node.tomean('l?nh làm');
        meanstrategy.highlight(node, 'm');
      }
    };
  },
  '-?': function (node) {
    if (!node.isspace(false) || (node.nE() && node.nE().gT().length > 1)) {
      node.tomean('d? cho');
    }
  },
  '??': function (node) {
    if (!node.isspace(false)) {
      node.tomean('thì ra');
      if (node.pE() && node.pE().tagName == 'BR') {
        node.tomean('Thì ra');
      }
      meanstrategy.highlight(node, 'm');
    }
  },
  '??': function (node) {
    if (!node.isspace(true)) {
      node.tomean('nang l?c');
    }
  },
  '?': function (node) {
    var thi = looper.search(node, true, 7, '?', true);
    if (thi != null) {
    }
    if (node.pE() != null) {
      if (/exran/.test(node.pE().id)) {
        if (instring(node.pE().innerHTML, this.database.level)) {
          node.tomean('d?ng');
          return;
        }
      } else if (this.database.level.contain(node.pE().gT().lastChar())) {
        node.tomean('d?ng');
        return;
      }
    }
    var balance = this.countname(false, node, 4) - this.countname(true, node, 4);
    if (balance > 0) {
      node.tomean('m?y ngu?i');
    } else if (balance < 0 || !node.near(true) || node.near(false)) {
      node.tomean('ch?');
      if (node.near(true) && !node.near(false)) {
        node.tomean('các lo?i');
      }
    } else {
      node.textContent = 'các lo?i';
    }
    meanstrategy.highlight(node, 'm');
  },
  '??': function (node) {
    if (!node.isspace(false) && !node.isspace(true)) {
      if (node.previousSibling && node.previousSibling.textContent.contain('...')) {
        node.tomean('vân vân');
      } else
      node.tomean('Ch? dã');
      meanstrategy.highlight(node, 'm');
    }
  },
  '?': function (node) {
    var thi = looper.search(node, true, 3, '?', true);
    if (thi && node.isspace(false)) {
      node.textContent = 'coi';
    }
    if (!node.isspace(false) && node.isspace(true)) {
      node.tomean('khi');
    }
  },
  '??': function (node) {
    if (!node.isspace(false) && !node.isspace(true)) {
      node.tomean('Cung dúng');
      meanstrategy.highlight(node, 'm');
    }
  },
  '??': function (node) {
    var nd = looper.search(node, false, 5, '?');
    if (nd) {
      nd.tomean('sau khi');
      node.textContent = '';
    }
  },
  '-??': function (node) {
    if (node.pE() && node.pE().isname() && node.isspace(false)) {
      swapnode(node, node.pE());
      if (pIsNewLine(node.pE())) {
        node.pE().textContent = 'Trong tay';
        return true;
      }
    }
  },
  '??': function (node) {
    if (node.pE() && node.pE().isname() && node.isspace(false)) {
      swapnode(node, node.pE());
      node.pE().tomean('trong lòng');
    }
  },
  '??': function (node) {
    if (node.pE() && node.pE().isname() && node.isspace(false)) {
      swapnode(node, node.pE());
      if (pIsNewLine(node.pE())) {
        node.pE().textContent = 'Sau lung';
      }
    }
  },
  '??': function (node) {
    if (node.pE() && node.pE().isname() && node.isspace(false)) {
      swapnode(node, node.pE());
      if (pIsNewLine(node.pE())) {
        node.pE().textContent = 'Bên c?nh';
      }
    }
  },
  '???': function (node) {
    if (node.pE() && node.pE().isname() && node.isspace(false)) {
      swapnode(node, node.pE());
      if (pIsNewLine(node.pE())) {
        node.pE().textContent = 'Bên c?nh';
      }
    }
  },
  '-??': function (node) {
    if (node.pE() && node.pE().isname() && node.isspace(false)) {
      swapnode(node, node.pE());
      if (pIsNewLine(node.pE())) {
        node.pE().innerHTML = 'Trong m?t';
      }
    }
  },
  '??': function (node) {
    if (node.pE() && node.pE().isname() && node.isspace(false)) {
      swapnode(node, node.pE());
      if (pIsNewLine(node.pE())) {
        node.pE().innerHTML = 'Trên thân';
      }
    }
  },
  '???': function (node) {
    if (node.pE() && node.pE().isname() && node.isspace(false)) {
      swapnode(node, node.pE());
      if (pIsNewLine(node.pE())) {
        node.pE().innerHTML = 'Trên thân';
      }
    }
  },
  '??': function (node) {
    if (node.pE() && node.pE().isname()) {
      swapnode(node, node.pE());
      if (pIsNewLine(node.pE())) {
        node.pE().innerHTML = 'Phía du?i';
      }
    }
  },
  '???': function (node) {
    return;
    if (node.pE() && node.pE().isname()) {
      swapnode(node, node.pE());
      if (pIsNewLine(node.pE())) {
        node.pE().innerHTML = 'Th? do?n c?a';
      } else {
        node.pE().innerHTML = 'th? do?n c?a';
      }
    }
  },
  '???': function (node) {
    var nd = looper.search(node, false, 2, '?', true);
    if (nd) {
      nd.innerHTML += ' ' + node.innerHTML;
      node.innerHTML = '';
    }
  },
  '??': function (node) {
    var nd = looper.search(node, false, 5, '?', true);
    if (nd) {
      nd.innerHTML += ' ' + node.innerHTML;
      node.innerHTML = '';
    }
  },
  '??': function (node) {
    var nd = looper.search(node, false, 3, '?', true);
    if (nd) {
      nd.innerHTML += ' ' + node.innerHTML;
      node.innerHTML = '';
    }
  },
  '??': function (node) {
    var nd = looper.search(node, false, 5, '?', true);
    if (nd) {
      nd.innerHTML += ' ' + node.innerHTML;
      node.innerHTML = '';
    }
  },
  '?': function (node) {
    if (node.nextSibling != null) {
      if (new RegExp(this.database.brk).test(node.nextSibling.textContent)) {
        node.tomean('dúng');
        meanstrategy.highlight(node, 'm');
      }
    }
  },
  '?': function (node) {
    if (!node.isspace(true)) {
      node.innerHTML = 'hoàn';
    }
  },
  '??': function (node) {
    if (!node.isspace(true)) {
      node.tomean('nói chi là');
      meanstrategy.highlight(node, 'm');
    }
  },
  '??': function (node) {
    var nd = looper.searchex(node, false, 3, '?', true);
    if (nd && !nd.innerHTML.contain('tru?c m?t')) {
      nd.innerHTML += ' tru?c m?t';
      node.innerHTML = '';
    }
  },
  '??': function (node) {
    var nd = looper.search(node, false, 3, '?', true);
    if (nd) {
      nd.innerHTML += ' ' + node.innerHTML;
      node.innerHTML = '';
    }
  },
  '??': function (node) {
    var nd = looper.search(node, false, 6, '?', true) || looper.search(node, false, 6, '?', true);
    if (nd) {
      nd.innerHTML += ' ' + node.innerHTML;
      node.innerHTML = '';
    } else
    if (node.pE() && node.pE().isname()) {
      swapnode(node, node.pE());
      if (pIsNewLine(node.pE())) {
        node.pE().innerHTML = 'Trong mi?ng';
      }
    }
  },
  '??': function (node) {
    if (pIsNewLine(node)) {
      node.innerHTML = 'Nói chung';
    }
  },
  '??': function (node) {
    var nd = looper.search(node, false, 6, '?', true);
    if (nd) {
      nd.innerHTML += ' ' + node.innerHTML;
      node.innerHTML = '';
    }
  },
  '?': function (node) {
    if (!node.innerHTML.contain('m?t')) return;
    var nd = looper.search(node, false, 3, '?') || looper.search(node, false, 3, '?');
    if (nd) {
      if (node.gT().length == 1) {
        nd.innerHTML += ' m?t';
        nd.innerHTML = nd.innerHTML.replace('m?t m?t', 'm?t');
        node.innerHTML = node.innerHTML.replace('m?t', '');
      }
      else {
        nd.innerHTML += ' ' + node.innerHTML;
        node.innerHTML = '';
      }
    }
  },
  '-??': function (node) {
    var nd = looper.search(node, false, 5, '?', true);
    if (nd) {
      nd.innerHTML += ' ' + node.innerHTML;
      node.tomean('');
    }
  },
  '??': function (node) {
    if (node.nextSibling != null) {
      if (!(new RegExp(this.database.brk).test(node.nextSibling.textContent))) {
        node.tomean('d?u d?i');
        meanstrategy.highlight(node, 'm');
      }
    }
  },
  '???': function (node) {
    if (node.nextSibling != null) {
      if (new RegExp(this.database.brk).test(node.nextSibling.textContent)) {
        node.textContent = 'm?i là d?i';
        meanstrategy.highlight(node, 'm');
      }
    }
  },
  '??': function (node) {
    if (node.nextSibling != null) {
      if (new RegExp(this.database.brk).test(node.nextSibling.textContent)) {
        node.tomean('cung dúng');
        meanstrategy.highlight(node, 'm');
      }
    }
  },
  '??': function (node) {
    while (node.nE() && node.isspace(true)) {
      node = node.nE();
      if (node.textContent.contain('b?t d?u')) {
        node.textContent = node.textContent.replace('b?t d?u', '');
      }
    }
  },
  '??': function (node) {
    if (node.pE() && node.pE().gT() && node.pE().gT().lastChar() == '?') {
      node.textContent = '';
    } else
    if (node.pE() && node.pE().innerHTML == 'b?t d?u') {
      node.textContent = '';
    } else
    if (node.pE() && node.pE().pE() && node.pE().pE().innerHTML == 'b?t d?u') {
      node.textContent = '';
    } else
    if (node.pE() && node.pE().pE().pE() && node.pE().pE().pE().innerHTML == 'b?t d?u') {
      node.textContent = '';
    } else
    if (looper.searchphrase(node, false, 6, '??', true)) {
      node.textContent = '';
    }
  },
  '??': function (node) {
    var nd = looper.search(node, false, 4, '?', true) || looper.searchphrase(node, false, 4, '??', true);
    if (nd) {
      if (true) {
        nd.textContent = node.innerHTML + ' ' + nd.innerHTML;
        node.tomean('');
        meanstrategy.highlight(nd, 'm');
      }
    }
  },
  '??': function (node) {
    if (!node.isspace(true)) {
      node.textContent = 'kh? nang';
    }
    if (false && node.pE() && node.pE().pE()) {
      if (node.pE().pE().gT().contain('?') && !node.pE().pE().innerHTML.contain('kh? nang')) {
        node.pE().pE().innerHTML += ' kh? nang';
        node.tomean('');
      } else if (node.pE().pE().pE() && node.pE().pE().pE().gT().contain('?') && !node.pE().pE().pE().innerHTML.contain('kh? nang')) {
        node.pE().pE().pE().innerHTML += ' kh? nang';
        node.tomean('');
      }
    }
  },
  '?': function (node) {
    if (node.pE() && node.isspace(false) && node.pE().gT().contain('?') && node.pE().gT().length == 4) {
      var pch = node.pE().gT();
      if (pch.contain('?') || pch.contain('?') || pch.contain('?') || pch.contain('?') || pch.contain('??') || pch.contain('?') || pch.contain('?'))
      {
        var m = /^(.*? .*?) c?a (.*?)$/i.exec(node.pE().innerHTML);
        if (m != null && node.nE() && node.nE().gT().length == 2) {
          node.innerHTML = node.nE().innerHTML + ' cùng ' + node.pE().innerHTML;
          node.nE().tomean('');
          node.pE().tomean('');
          meanstrategy.highlight(node, 'm');
        }
      }
    }
  },
  '?': function (node) {
    if (node.pE() && node.nE() && node.isspace(true) && node.isspace(false)) {
      if (node.pE().gT() == node.nE().gT()) {
        node.textContent = 'hay không';
      }
    }
  },
  '??': function (node) {
    if (node.nE() && node.nE().textContent.contain('tr? nên')) {
      node.tomean('');
    }
  },
  '?': function (node) {
    if (node.nE() && node.nE().gT() == '?' && node.nE().nE()) {
      var c = node.nE().nE();
      if (c.gT() in this.database.carbrand) {
        node.tomean('lái');
        node.nE().tomean('');
        c.tomean(this.database.carbrand[c.gT()]);
        meanstrategy.highlight(node, 'm');
      } else {
        var node2 = node;
        for (var i = 0; i < 3; i++) {
          node2 = node2.nE();
          if (node2 == null) return;
          if (!node.isspace(false)) return;
          if (instring(node2.gT(), '??')) {
            node.tomean('lái');
            node.nE().tomean('');
            meanstrategy.highlight(node, 'm');
            return;
          }
        }
      }
    } else if (node.nE()) {
      var c = node.nE();
      if (c.gT() in this.database.carbrand) {
        node.tomean('lái');
        c.tomean(this.database.carbrand[c.gT()]);
        meanstrategy.highlight(node, 'm');
      } else {
        var node2 = node;
        for (var i = 0; i < 3; i++) {
          node2 = node2.nE();
          if (node2 == null) return;
          if (!node.isspace(false)) return;
          if (instring(node2.gT(), '??')) {
            node.tomean('lái');
            meanstrategy.highlight(node, 'm');
            return;
          }
        }
      }
    }
  },
  '??': function (node) {
    if (node.pE() && node.pE().containName()) {
      node.tomean('tr? cho');
    }
  },
  '???': function (node) {
    if (!node.isspace(true)) {
      node.tomean('chính là ta');
    }
  },
  '??': function (node) {
    if (!node.isspace(false)) {
      node.tomean('nói xong');
    }
  },
  '???': function (node) {
    if (node.nextSibling != null) {
      if (new RegExp(this.database.brk).test(node.nextSibling.textContent)) {
        node.tomean('th?t xin l?i');
        meanstrategy.highlight(node, 'm');
      }
    }
  },
  'numberpow': function (node) {
    if (node.gT().length == 1) {
      node.tomean('l?n');
    } else {
      var bt = node.gT().substring(1);
      if (node.nE()) {
        bt += node.nE().gT();
        node.nE().tomean('');
      }
      tse.send('002', bt, function () {
        node.tomean('l?n ' + this.down.split('=') [1]);
      });
    }
  },
  'faction': function (node, find, replace) {
    if (!setting.factionfilter) return;
    if (node.pE() && node.pE().containName()) {
      return;
    }
    if (this.countname(false, node, 1)) {
      if (find != '') {
        node.textContent = node.innerHTML.replace(find, replace);
      } else {
        node.textContent = replace;
      }
      this.highlight(node, 'f');
      meanstrategy.recognized[node.id] = {
        type: 'faction',
        range: [
          node.pE(),
          node
        ]
      }
    } else if (node.pE() != null) {
      if (!node.isspace(false)) return;
      if (node.pE().gT().length == 1) {
        node.pE().containHan(function () {
          if (find != '') {
            node.textContent = node.innerHTML.replace(find, replace);
          } else {
            node.textContent = replace;
          }
          node.pE().textContent = titleCase(node.pE().gH());
          meanstrategy.recognized[node.id] = {
            type: 'faction',
            range: [
              node.pE(),
              node
            ]
          }
          if (node.pE().pE() != null && node.pE().pE().gT().length == 1 && node.pE().isspace(false)) {
            node.pE().pE().containHan(function () {
              node.pE().pE().textContent = titleCase(node.pE().pE().gH());
              meanstrategy.collected += titleCase(node.pE().pE().gH() + ' ' + node.pE().gH()) + ' ' + node.gH() + '\n';
              meanstrategy.recognized[node.id].range.unshift(node.pE().pE());
            }, function () {
              meanstrategy.collected += titleCase(node.pE().gH()) + ' ' + node.gH() + '\n';
            });
          } else {
            meanstrategy.collected += titleCase(node.pE().gH()) + ' ' + node.gH() + '\n';
          }
          meanstrategy.highlight(node, 'f');
        });
      } else if (node.isspace(false))
      node.pE().containHan(function () {
        if (find != '') {
          node.textContent = node.innerHTML.replace(find, replace);
        } else {
          node.textContent = replace;
        }
        node.pE().textContent = titleCase(node.pE().gH());
        meanstrategy.collected += titleCase(node.pE().gH()) + ' ' + node.gH() + '\n';
        meanstrategy.recognized[node.id] = {
          type: 'faction',
          range: [
            node.pE(),
            node
          ]
        }
        meanstrategy.highlight(node, 'f');
      });
    }
  },
  recognized: {
  },
  factions: '?????????????????????'.split('').concat(['??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??']),
  people: function (node, leng) {
    if (!setting.peoplefilter) return;
    if (node.pE() != null) {
      if (node.pE().getAttribute('aname') == '2') {
        this.testcommon([node.pE(),
        node]);
        return;
      }
      if (node.pE().gT().length == 1 && this.surns.indexOf(node.pE().gT()) > - 1) return;
    }
    if (node.isname()) return;
    if (this.testignore(node)) return;
    if (node.gT() [0] == '?' || node.gT() [0] == '?') {
      if (node.pE() && this.containnumber(node.pE())) {
        return;
      }
    }
    var maxleng = (leng == 1) ? 3 : 4;
    if (node.gT().length > leng) {
      if (node.gT().length > leng + 2) return;
       else {
        if (node.nE() != null && node.nE().gT().length == 1) {
          if (node.nextSibling.textContent != ' ') return;
          if (node.nE().gT().length + node.gT().length <= maxleng)
          node.nE().containHan(function (down) {
            if (!meanstrategy.iscommsurn(node.gT().charAt(0)) && meanstrategy.testcommon([node,
            node.nE()]) < 3) {
            } else if (meanstrategy.testcommon([node,
            node.nE()]) > 1 && (meanstrategy.testcommon([node,
            node.nE()]) >= meanstrategy.testcommon([node])) || meanstrategy.testcommon([node,
            node.nE()]) > 3) {
              node.textContent = titleCase(node.gH());
              node.nE().textContent = titleCase(node.nE().gH());
              meanstrategy.highlight(node, 'o');
              node.setAttribute('aname', '2');
              analyzer.update([node,
              node.nE()].sumChinese(), 1);
            } else if (meanstrategy.iscommsurn(node.gT().charAt(0))) {
              if (node.gH() == node.innerHTML.toLowerCase()) {
                if (node.nE().gH() == node.nE().innerHTML.toLowerCase()) {
                  node.textContent = titleCase(node.gH());
                  node.nE().textContent = meanstrategy.testsuffix(node.nE().gT(), titleCase(node.nE().gH()));
                  meanstrategy.highlight(node, 'o');
                  node.setAttribute('aname', '2');
                  analyzer.update([node,
                  node.nE()].sumChinese(), 1);
                }
              }
            }
          });
        }
        if (node.gT().length <= maxleng) {
          if (this.testcommon([node]) > 3 && this.iscommsurn(node.gT().charAt(0))) {
            node.containHan(function (down) {
              if (down.split('/').length < 3) {
                node.textContent = meanstrategy.testsuffix(node.gT(), titleCase(node.gH()));
                meanstrategy.highlight(node, 'o');
                analyzer.update(node.gT(), 1);
              }
            }, null, true);
          }
        }
      }
    } else
    if (node.nE() != null && node.nE().gT().length == 1) {
      if (node.nextSibling.textContent != ' ') return;
      if (node.nE().gT().length + node.gT().length <= maxleng)
      node.nE().containHan(function () {
        if (!meanstrategy.iscommsurn(node.gT()) && meanstrategy.testcommon([node,
        node.nE()]) < 2) {
        } else {
          var na = node.gT() + node.nE().gT();
          if (!(na in meanstrategy.addedname)) {
            meanstrategy.addedname[na] = true;
            meanstrategy.addname(na, [
              node,
              node.nE()
            ]);
          }
          node.textContent = titleCase(node.gH());
          node.nE().textContent = meanstrategy.testsuffix(node.nE().gT(), titleCase(node.nE().gH()));
          meanstrategy.highlight(node, 'o');
          node.setAttribute('aname', '2');
          analyzer.update([node,
          node.nE()].sumChinese(), 1);
        }
      }, function () {
        if (!(node.gT() + node.nE().gT() in meanstrategy.addedname) && meanstrategy.testcommon([node,
        node.nE()]) > 3) {
          var na = node.gT() + node.nE().gT();
          meanstrategy.highlight(node, 'o');
          meanstrategy.addedname[na] = true;
          needbreak = true;
          namew.value = '$' + na + '=' + titleCase(node.gH() + ' ' + node.nE().gH()) + '\n' + namew.value;
          saveNS();
          excute();
        }
      });
      if (node.nE().nE() != null && node.nE().nE().gT().length == 1) {
        if (node.nE().nextSibling.textContent != ' ') return;
        if (this.testcommon([node,
        node.nE(),
        node.nE().nE()]) > 2 || (this.testcommon([node,
        node.nE()]) <= this.testcommon([node,
        node.nE(),
        node.nE().nE()])))
        if (node.nE().nE().gT().length + node.nE().gT().length + node.gT().length <= maxleng)
        node.nE().nE().containHan(function () {
          var na = node.gT() + node.nE().gT() + node.nE().nE().gT();
          if (!(na in meanstrategy.addedname)) {
            meanstrategy.addedname[na] = true;
            meanstrategy.addname(na, [
              node,
              node.nE(),
              node.nE().nE()
            ]);
          }
          node.nE().setAttribute('aname', '2');
          node.nE().nE().textContent = meanstrategy.testsuffix(node.nE().nE().gT(), titleCase(node.nE().nE().gH()));
          analyzer.update([node,
          node.nE(),
          node.nE().nE()].sumChinese(), 1);
        });
      }
    } else if (node.nE() != null && node.nE().gT().length == 2) {
      if (node.nextSibling.textContent != ' ') return;
      if (node.nE().gT().length + node.gT().length > maxleng) return;
      node.nE().containHan(function () {
        if (!meanstrategy.iscommsurn(node.gT()) && meanstrategy.testcommon([node,
        node.nE()]) < 2) {
        } else {
          var na = node.gT() + node.nE().gT();
          if (!(na in meanstrategy.addedname)) {
            meanstrategy.addedname[na] = true;
            meanstrategy.addname(na, [
              node,
              node.nE()
            ]);
          }
          node.textContent = titleCase(node.gH());
          node.nE().textContent = meanstrategy.testsuffix(node.nE().gT(), titleCase(node.nE().gH()));
          meanstrategy.highlight(node, 'o');
          node.setAttribute('aname', '2');
          analyzer.update([node,
          node.nE()].sumChinese(), 1);
        }
      }, function () {
        if (!(node.gT() + node.nE().gT() in meanstrategy.addedname) && meanstrategy.testcommon([node,
        node.nE()]) > 3) {
          meanstrategy.highlight(node, 'o');
          var na = node.gT() + node.nE().gT();
          meanstrategy.addedname[na] = true;
          needbreak = true;
          namew.value = '$' + na + '=' + titleCase(node.gH() + ' ' + node.nE().gH()) + '\n' + namew.value;
          saveNS();
          excute();
        }
      });
    }
  },
  commsurn: 155,
  iscommsurn: function (chi) {
    return this.surns.indexOf(chi) <= this.commsurn;
  },
  surns: '???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????'
  + '?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????',
  surns2: '????????????????????????????????' +
  '????????????????????????????????' +
  '????????????????????????????????' +
  '????????????????????????????????' +
  '????????????????????????????????' +
  '????????????????????????',
  skill: function (node, lastchar) {
    if (!setting.skillfilter) return;
    if (this.skillignore.indexOf(node.gT()) > - 1) return;
    var maxleng = 5;
    if (node.cn.lastChar() == '?') {
      maxleng++;
    }
    var cumulated = node.gT().length;
    var acceptrange = 2;
    if (node.gT().length < 2) {
      acceptrange = 3;
    }
    node.containHan(function (down0) {
      if (node.pE() != null) {
        if (!node.isspace(false)) return;
        if (cumulated + node.pE().gT().length <= maxleng) {
          node.pE().containHan(function (down1) {
            if (down1.split('/').length > acceptrange && down1.indexOf(node.pE().gH()) != 0 && down1.split(node.pE().gH()).length < 3) return;
            cumulated += node.pE().gT().length;
            node.pE().textContent = meanstrategy.skillcasing(node.pE().gH());
            node.textContent = meanstrategy.skillcasing(node.gH());
            meanstrategy.highlight(node, 's');
            if (node.pE().pE() != null && node.pE().isspace(false) && cumulated + node.pE().pE().gT().length <= maxleng)
            {
              node.pE().pE().containHan(function (down2) {
                if (down2.split('/').length > acceptrange && down2.indexOf(node.pE().pE().gH()) != 0 && down2.split(node.pE().pE().gH()).length < 3) return;
                cumulated += node.pE().pE().gT().length;
                node.pE().pE().textContent = meanstrategy.skillcasing(node.pE().pE().gH());
                if (node.pE().pE().pE() != null) {
                  if (!node.pE().pE().isspace(false)) return;
                  if (cumulated + node.pE().pE().pE().gT().length <= maxleng) {
                    node.pE().pE().pE().containHan(function (down3) {
                      if (down3.split('/').length > acceptrange && down3.indexOf(node.pE().pE().pE().gH()) != 0 && down3.split(node.pE().pE().pE().gH()).length < 3) return;
                      node.pE().pE().pE().textContent = meanstrategy.skillcasing(node.pE().pE().pE().gH());
                    }, null, true);
                  }
                }
              }, function () {
                if (node.gT().length + node.pE().gT().length < 3) {
                  node.textContent = node.gH();
                  node.pE().textContent = node.pE().gH();
                }
              }, true);
            } else {
              if (node.gT().length + node.pE().gT().length < 3) {
                node.textContent = node.gH();
                node.pE().textContent = node.pE().gH();
              }
            }
          }, null, true);
        }
      }
    }, null, false);
  },
  skills: '?????????????????????'.split('').concat('??????????'.splitn(2)),
  skillignore: '??????'.splitn(2),
  skillcasing: function (translated) {
    if (!!setting.skilluppercase) {
      return titleCase(translated);
    } else return translated;
  },
  item: function (node, lastchar) {
    if (!setting.itemfilter) return;
    if (this.itemignore.indexOf(node.gT()) > - 1) return;
    var maxleng = 5;
    var cumulated = node.gT().length;
    var acceptrange = 2;
    if (node.gT().length < 2) {
      acceptrange = 3;
    }
    node.containHan(function (down0) {
      if (node.pE() != null) {
        if (!node.isspace(false)) return;
        if (cumulated + node.pE().gT().length <= maxleng) {
          node.pE().containHan(function (down1) {
            if (down1.split('/').length > acceptrange && down1.indexOf(node.pE().gH()) != 0 && down1.split(node.pE().gH()).length < 3) return;
            cumulated += node.pE().gT().length;
            node.pE().textContent = meanstrategy.skillcasing(node.pE().gH());
            node.textContent = meanstrategy.skillcasing(node.gH());
            meanstrategy.highlight(node, 's');
            if (node.pE().pE() != null && node.pE().isspace(false) && cumulated + node.pE().pE().gT().length <= maxleng)
            {
              node.pE().pE().containHan(function (down2) {
                if (down2.split('/').length > acceptrange && down2.indexOf(node.pE().pE().gH()) != 0 && down2.split(node.pE().pE().gH()).length < 3) return;
                cumulated += node.pE().pE().gT().length;
                node.pE().pE().textContent = meanstrategy.skillcasing(node.pE().pE().gH());
                if (node.pE().pE().pE() != null) {
                  if (!node.pE().pE().isspace(false)) return;
                  if (cumulated + node.pE().pE().pE().gT().length <= maxleng) {
                    node.pE().pE().pE().containHan(function (down3) {
                      if (down3.split('/').length > acceptrange && down3.indexOf(node.pE().pE().pE().gH()) != 0 && down3.split(node.pE().pE().pE().gH()).length < 3) return;
                      node.pE().pE().pE().textContent = meanstrategy.skillcasing(node.pE().pE().pE().gH());
                    }, null, true);
                  }
                }
              }, function () {
                if (node.gT().length + node.pE().gT().length < 3) {
                  node.textContent = node.gH();
                  node.pE().textContent = node.pE().gH();
                }
              }, true);
            } else {
              if (node.gT().length + node.pE().gT().length < 3) {
                node.textContent = node.gH();
                node.pE().textContent = node.pE().gH();
              }
            }
          }, null, true);
        }
      }
    }, null, false);
  },
  items: '',
  itemignore: '',
  entity: function (node, lastchar) {
    if (!setting.entityfilter) return;
    if (this.entityignore.indexOf(node.gT()) > - 1) return;
    var maxleng = 5;
    var cumulated = node.gT().length;
    var acceptrange = 2;
    if (node.gT().length < 2) {
      acceptrange = 3;
    }
    node.containHan(function (down0) {
      if (node.pE() != null) {
        if (!node.isspace(false)) return;
        if (cumulated + node.pE().gT().length <= maxleng) {
          node.pE().containHan(function (down1) {
            if (down1.split('/').length > acceptrange && down1.indexOf(node.pE().gH()) != 0 && down1.split(node.pE().gH()).length < 3) return;
            cumulated += node.pE().gT().length;
            node.pE().textContent = meanstrategy.skillcasing(node.pE().gH());
            node.textContent = meanstrategy.skillcasing(node.gH());
            meanstrategy.highlight(node, 's');
            if (node.pE().pE() != null && node.pE().isspace(false) && cumulated + node.pE().pE().gT().length <= maxleng)
            {
              node.pE().pE().containHan(function (down2) {
                if (down2.split('/').length > acceptrange && down2.indexOf(node.pE().pE().gH()) != 0 && down2.split(node.pE().pE().gH()).length < 3) return;
                cumulated += node.pE().pE().gT().length;
                node.pE().pE().textContent = meanstrategy.skillcasing(node.pE().pE().gH());
                if (node.pE().pE().pE() != null) {
                  if (!node.pE().pE().isspace(false)) return;
                  if (cumulated + node.pE().pE().pE().gT().length <= maxleng) {
                    node.pE().pE().pE().containHan(function (down3) {
                      if (down3.split('/').length > acceptrange && down3.indexOf(node.pE().pE().pE().gH()) != 0 && down3.split(node.pE().pE().pE().gH()).length < 3) return;
                      node.pE().pE().pE().textContent = meanstrategy.skillcasing(node.pE().pE().pE().gH());
                    }, null, true);
                  }
                }
              }, function () {
                if (node.gT().length + node.pE().gT().length < 3) {
                  node.textContent = node.gH();
                  node.pE().textContent = node.pE().gH();
                }
              }, true);
            } else {
              if (node.gT().length + node.pE().gT().length < 3) {
                node.textContent = node.gH();
                node.pE().textContent = node.pE().gH();
              }
            }
          }, null, true);
        }
      }
    }, null, false);
  },
  entities: '',
  entityignore: '',
  ignore: '??????????????????????????????????????????????????????????????????????????????????????????????',
  connectignore: '?????',
  ignore2: '????????????????',
  havemean: '??'.splitn(2),
  testignore: function (node) {
    if (instring(node.gT(), meanstrategy.ignore)) {
      if (!instring(node.gT(), meanstrategy.ignore2) || meanstrategy.testcommon([node.pE(),
      node]) < 2) {
        return true;
      }
    }
    return false;
  },
  testignorechi: function (chi) {
    if (instring(chi, meanstrategy.ignore)) {
      return true;
    }
    return false;
  },
  suffix: '???????????????????'.split('').concat('??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????'.splitn(2)),
  testsuffix: function (text, translated) {
    for (var i = 0; i < text.length; i++) {
      if (this.suffix.indexOf(text.substring(i)) > - 1) {
        return lowerNLastWord(translated, text.length - i);
      }
    }
    return translated;
  },
  testcommon: function (nodecomb) {
    var len = nodecomb.length;
    var comb = nodecomb.sumChinese();
    if (comb in this.commondata) {
      return this.commondata[comb];
    }
    var count = 0;
    if (len == 1) {
      count = this.maincontent.qq('[t="' + nodecomb[0].cn + '"]').length;
    }
    if (len == 2) {
      count = this.maincontent.qq('[t="' + nodecomb[0].cn + '"]+[t="' + nodecomb[1].cn + '"]').length;
    }
    if (len == 3) {
      count = this.maincontent.qq('[t="' + nodecomb[0].cn + '"]+[t="' + nodecomb[1].cn + '"]+[t="' + nodecomb[2].cn + '"]').length;
    }
    this.commondata[comb] = count;
    return count;
  },
  prepositionmover: function (node) {
    var cn = node.gT();
    if (node.getAttribute('moved') == 'true') return;
    if (this.database.phasemarginr.c(cn.substring(cn.length - 2)) || this.database.phasemarginr.c(cn.substring(cn.length - 1))) {
      if (instring(cn, meanstrategy.ignore)) return;
      if (!node.isspace(false)) return;
      var nd = looper.searchex(node, false, 3, this.database.phasemarginul, true);
      if (nd) {
        nd.innerHTML += ' ' + node.innerHTML;
        node.innerHTML = '';
        this.highlight(nd, 'm');
        node.setAttribute('moved', 'true');
        return;
      } else if (node.pE() && node.pE().isname()) {
        swapnode(node, node.pE());
        node.setAttribute('moved', 'true');
        this.highlight(node, 'm');
      } else if (node.pE() && node.pE().gT().length > 1 && (node.pE().pE() && node.pE().pE().tagName == 'BR' || !node.pE().pE())) {
        swapnode(node, node.pE());
        node.setAttribute('moved', 'true');
        this.highlight(node, 'm');
      }
    }
  },
  'commondata': {
  },
  'invoker': false,
  '??': function (node) {
    this.faction(node, '', 'Ðao môn');
  },
  'addedname': {
  },
  '??': function (node) {
    node.tomean('thu?ng thiên');
  },
  'vpdatabase': {
  },
  '??': function (node) {
    node.tomean('nhân v?t');
  },
  '??': function (node) {
    this.faction(node, '', 'Thiên c?nh');
  },
  '??': function (node) {
    this.faction(node, '', 'Th?n c?nh');
  },
  '??': function (node) {
    this.faction(node, '', 'Thánh c?nh');
  },
  '??': function (node) {
    this.faction(node, '', 'Ð?i Ð?');
  },
  '??': function (node) {
    this.faction(node, '', 'Ð?o Quân');
  },
  '??': function (node) {
    this.faction(node, '', 'Chúa T?');
  },
  '??': function (node) {
    this.faction(node, '', 'N? Ð?');
  },
  '??': function (node) {
    this.faction(node, '', 'Vuong Tri?u');
  },
  '??': function (node) {
    this.faction(node, '', 'Ð? Tri?u');
  },
  '??': function (node) {
    this.faction(node, '', 'Th?n Tri?u');
  },
  '??': function (node) {
    this.faction(node, '', 'Thiên Tri?u');
  },
  '??': function (node) {
    this.faction(node, '', 'Huy?n c?nh');
  },
  '??': function (node) {
    this.faction(node, '', 'H?a tông');
  },
  testenglish: function (node) {
    var currentnode = node;
    if (this.surns.indexOf(node.cn[0]) >= 0) {
      return;
    }
    var walked = 0;
    var nodlist = [
    ];
    while (currentnode != null) {
      walked++;
      if (walked > 5) break;
      if (engtse.alliseng(currentnode.gT())) {
        nodlist.push(currentnode);
      } else {
        break;
      }
      if (!currentnode.isspace(true)) break;
      currentnode = currentnode.nE();
    }
    if (nodlist.length < 2) return;
    var chi = nodlist.sumChinese('');
    if (chi.length < 3) return;
    if (this.testcommon(nodlist) < 3) return;
    var engname = titleCase(engtse.trans(chi));
    node.textContent = engname;
    node.setAttribute('v', engname);
    for (var i = 1; i < nodlist.length; i++) {
      node.setAttribute('h', node.gH() + ' ' + nodlist[i].gH());
      node.setAttribute('t', node.gT() + nodlist[i].gT());
      node.cn = node.gT() + nodlist[i].gT();
      nodlist[i].remove();
    }
    meanstrategy.highlight(node, 'e');
  },
  containnumber: function (node) {
    if (this.database.numrex.test(node.gT())) {
      return true;
    }
    return false;
  },
  containmargin: function (node) {
  },
  database: {
    preposition: {
      '?': 'này',
      '?': 'không'
    },
    phasemarginul: '?????'.split(''),
    phasemarginl: '??????????????'.split(''),
    phasemarginr: '???????????'.split('').concat('??'.splitn(2)),
    getmean: function (chi, calb) {
      if (chi in this) {
        calb(this[chi]);
      } else {
        tse.send('005', chi, function (d) {
          if (this.down != 'false') {
            meanstrategy.database[this.up] = this.down.trim();
          }
          calb(this.down.trim());
        });
      }
    },
    brk: '[,.“”!?]',
    scope: {
      open: '[«??<?[‘:·]',
      close: '[»??>?]’??]'
    },
    pronoun: '??????',
    numbers: '???????????',
    numrex: /[0-9\.\-\,????????????????]+/,
    level: 'ABCDEFGHIKabcdefghikSs123456789?',
    carbrand: (function () {
      var names = ('??=ACURA/??????=ALFA ROMEOS/?????=ASTON MARTIN/??=AUDI/??=BENTLEY/??=BMW/???=BUGATTI/??=BUICK/???=BYD/????=CADILLAC/' +
      '???=CHEVROLET/????=CHRYSLER/???=CITROEN/??=DODGE/???=FERRARI/???=FIAT/??=FORD/??=HONDA/??=HUMMER/??=HYUNDAI/????=INFINITI/???=IVECO/'
      + '??=JAGUAR/??=JEEP/??=KIA/????=LAMBORGHINI/???=LANCIA/??=LAND ROVER/????=LEXUS/??=LINCOLN/???=LORINSER/??=LOTUS/????=MASERATI/' +
      '???=MAYBACH/???=MAZDA/??=MERCEDES-BENZ/??=MERCURY/??=MORRISGARAGES/??=MITSUBISHI/??=NISSAN/??=OPEL/???=PAGANI/??=PEUGEOT/????=PLYMOUTH/' +
      '????=PONTIAC/???=PORSCHE/??=RENAULT/????=ROLLS ROYCE/??=ROVER/??=SAAB/??=SPYKER/??=SSANGYONG/???=SUBARU/??=SUZUKI/??=TOYOTA/???=TESLA/' +
      '?????=VAUXHALL/???=VENTURI/??=VOLKSWAGEN/???=VOLVO/??=xe d?p di?n/??=Veneno').split('/');
      var obj = {
      };
      for (var i = 0; i < names.length; i++) {
        var name = names[i].split('=');
        obj[name[0]] = name[1];
      }
      obj['names'] = ('??/??????/?????/??/??/??/???/??/???/????/???/????/???/??/???/???/??/??/' +
      '??/??/????/???/??/??/??/????/???/??/????/??/???/??/????/???/???/??/??/??/??/??/' +
      '??/???/??/????/????/???/??/????/??/??/??/??/???/??/??/???/?????/???/??/???/??/??').split('/');
      return obj;
    }) (),
    english: '?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????'
  },
  addname: function (level, base, name, script) {
    if (script == 'titleCase') {
      name = titleCase(name);
    }
    if (script == 'firstWord') {
      name = name[0].toUpperCase() + name.substring(1);
    }
    if (script == 'firstTwoWord') {
      var c2 = name.indexOf(' ') + 1;
      name = name[0].toUpperCase() + name.substr(1, c2 - 1) + name[c2].toUpperCase() + name.substring(c2 + 1);
    }
    if (script == 'lowerAll') {
      name = name.toLowerCase();
    }
    if (level == 0) {
      namew.value += '\n' + base + '=' + name;
    }
    if (level == 1) {
      namew.value += '\n@' + base + '=' + name;
    }
    if (level == 2 || level == 3) {
      namew.value += '\n$' + base + '=' + name;
    }
    saveNS();
  }
};
function meanengine(ln) {
  if (meanengine.checkparse(ln)) {
    return;
  }
  var baseln = ln;
  if (/>?.*?\+.*?=.*/.test(ln)) {
    ln = meanengine.shorthand(ln);
  }
  if (/.*?\{0\}.*?=.*/.test(ln)) {
    ln = meanengine.shorthand2(ln);
  }
  var delim = '->';
  ln = ln.split(delim);
  if (ln.length < 2 || ln[0].contain('=')) {
    delim = '=';
    ln = baseln.split('=');
    if (ln.length < 2) {
      console.log(ln + ' không d?y d?, b? qua.');
      return;
    }
    if (!ln[0].contain('{')) {
      return meanenginelight(baseln);
    }
  }
  var lefts = ln[0].replace(' ', '');
  var stack = [
  ];
  var mstack = [
  ];
  var asigner = /@(.+?):(\d+)/.exec(lefts);
  if (asigner) {
    lefts = lefts.substr(asigner[0].length);
  }
  var regex = {
    base: /{.*?}(\[.+?\])?/g,
    extend: /{(\d+)(?:\.\.|\~)(\d+)}/,
    exactwidth: /{(\d+)}/,
    havechar: /{\*(.+?)}/,
    haveword: /{\~(.+?)}/,
    lastword: /^{:(.+?)}$/,
    firstword: /^{(.+?):}$/,
    firstandlastword: /^{(.+?):(.+?)}$/,
    mtransform: /{(\d+)->(.*?)}/,
    mremove: /{(\d+_?)X}/,
    mshort: /{(\d+)->@}/,
    mappend: /{(\d+)\+(.+?)}/,
    mreplace: /{(\d+)\-(.+?)}/,
    mrepapp: /{(\d+)\-(.+?)\+(.+?)}/,
    mprepend: /{(.+?)\+(\d+)}/,
    mreppre: /{(.+?)\+(\d+)\-(.+?)}/,
    mrepins: /{(.+?)\+(\d+)\-(.+?)\+(.+?)}/,
    minside: /{(.+?)\+(\d+)\+(.+?)}/,
    mdefault: /{(\d+):}/
  };
  var typing = {
    '{N}': 'name',
    '{PN}': 'proname',
    '{P}': 'pronoun',
    '{S}': 'number',
    '{D}': 'deter',
    '{SD}': 'numdeter',
    '{D-}': 'deter-',
    '{L}': 'locat',
    '{*L}': 'lastlocat',
    '{L1}': 'locat1',
    '{L2}': 'locat2',
    '{T}': 'subw',
    '{R}': 'relv',
    '{R1}': 'relv1',
    '{R2}': 'relv2',
    '{R3}': 'relv3',
    '{*}': 'unlim',
    '{~}': 'unlim',
    '{SW}': 'stw',
    '{t:F}': 'faction',
    '{t:I}': 'item',
    '{t:S}': 'skill',
    '{VI}': 'tviet'
  }
  var m;
  var m2;
  var isEnableAnl = false;
  do {
    m = regex.base.exec(lefts);
    if (m) {
      var mdf = false;
      var token = m[0];
      if (m[1]) {
        token = token.substr(0, token.length - m[1].length);
        if (m[1][1] == ':') {
          var m3 = m[1].substr(2, m[1].length - 3).toLowerCase();
          mdf = {
            type: 'pos',
            postype: m3
          };
        } else {
          var m3 = /^\[(\d+)?(,)?(\d+)?\]$/.exec(m[1]);
          if (m3 == null) m3 = [
          ];
          if (m3[1] && m3[2] && m3[3]) {
            mdf = {
              type: 'length',
              min: parseInt(m3[1]),
              max: parseInt(m3[3])
            };
          } else if (m3[2] && m3[3]) {
            mdf = {
              type: 'length',
              min: 0,
              max: parseInt(m3[3])
            };
          } else if (m3[1] && m3[2]) {
            mdf = {
              type: 'length',
              min: parseInt(m3[1]),
              max: 99
            };
          } else if (m3[1]) {
            mdf = {
              type: 'length',
              min: parseInt(m3[1]),
              max: parseInt(m3[1])
            };
          } else {
            mdf = {
              type: 'have',
              text: m[1].substr(1, m[1].length - 2)
            };
          }
        }
      }
      if (token in typing) {
        stack.push({
          type: typing[token],
          modifier: mdf
        });
        continue;
      }
      m2 = regex.extend.exec(token);
      if (m2) {
        stack.push({
          type: 'extend',
          min: parseInt(m2[1]),
          max: parseInt(m2[2]),
          modifier: mdf
        });
        continue;
      }
      m2 = regex.exactwidth.exec(token);
      if (m2) {
        stack.push({
          type: 'extend',
          min: parseInt(m2[1]),
          max: parseInt(m2[1]),
          modifier: mdf
        });
        continue;
      }
      m2 = regex.havechar.exec(token);
      if (m2) {
        stack.push({
          type: 'havechar',
          char: m2[1],
          modifier: mdf
        });
        continue;
      }
      m2 = regex.haveword.exec(token);
      if (m2) {
        stack.push({
          type: 'haveword',
          word: m2[1],
          modifier: mdf
        });
        continue;
      }
      m2 = regex.lastword.exec(token);
      if (m2) {
        stack.push({
          type: 'lastword',
          word: m2[1],
          modifier: mdf
        });
        continue;
      }
      m2 = regex.firstword.exec(token);
      if (m2) {
        stack.push({
          type: 'firstword',
          word: m2[1],
          modifier: mdf
        });
        continue;
      }
      m2 = regex.firstandlastword.exec(token);
      if (m2) {
        stack.push({
          type: 'firstlast',
          word1: m2[1],
          word2: m2[2],
          modifier: mdf
        });
        continue;
      }
      stack.push({
        type: 'exact',
        word: token.substr(1, token.length - 2)
      });
    }
  } while (m != null);
  if (lefts[0] == '>') {
    stack[0].isfirst = true;
  }
  if (lefts[lefts.length - 1] == '<') {
    stack[stack.length - 1].islast = true;
  }
  ln.shift();
  var rights = ln.join(delim);
  do {
    m = regex.base.exec(rights);
    if (m) {
      var token = m[0];
      m2 = regex.mshort.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'short'
        });
        continue;
      }
      m2 = regex.mtransform.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'transform',
          word: m2[2]
        });
        continue;
      }
      m2 = regex.mremove.exec(token);
      if (m2) {
        if (m2[1].lastChar() == '_') {
          mstack.push({
            nodeid: parseInt(m2[1]) - 1,
            type: 'removenode'
          });
        } else {
          mstack.push({
            nodeid: parseInt(m2[1]) - 1,
            type: 'remove'
          });
        }
        continue;
      }
      m2 = regex.mappend.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'append',
          word: m2[2]
        });
        continue;
      }
      m2 = regex.mrepapp.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'repapp',
          repword: m2[2],
          word: m2[3]
        });
        continue;
      }
      m2 = regex.mrepins.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[2]) - 1,
          type: 'repins',
          repword: m2[3],
          lword: m2[1],
          rword: m2[4]
        });
        continue;
      }
      m2 = regex.mreppre.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[2]) - 1,
          type: 'reppre',
          repword: m2[3],
          word: m2[1]
        });
        continue;
      }
      m2 = regex.minside.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[2]) - 1,
          type: 'inside',
          lword: m2[1],
          rword: m2[3]
        });
        continue;
      }
      m2 = regex.mprepend.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[2]) - 1,
          type: 'prepend',
          word: m2[1]
        });
        continue;
      }
      m2 = regex.mreplace.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'replace',
          repword: m2[2]
        });
        continue;
      }
      m2 = regex.mdefault.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'default'
        });
        continue;
      }
      mstack.push({
        nodeid: parseInt(token.substr(1, token.length - 2)) - 1,
        type: 'retain'
      });
    }
  } while (m != null);
  if (mstack.length < stack.length) {
    for (var i = 0; i < stack.length; i++) {
      var fl = false;
      for (var j = 0; j < mstack.length; j++) {
        if (mstack[j].nodeid == i) {
          fl = true;
          break;
        }
      }
      if (!fl) {
        mstack.push({
          nodeid: i,
          type: 'remove'
        });
      }
    }
  }
  if (asigner) {
    if (asigner[1] in meanstrategy) {
      if (!meanstrategy[asigner[1]].stack) {
        (function (w) {
          meanstrategy['_def-' + w] = meanstrategy[w];
          meanstrategy[w] = function (root) {
            for (var i2 = 0; i2 < meanstrategy[w].stack.length; i2++) {
              if (meanstrategy[w].stack[i2](root)) {
                break;
              }
            }
          }
          meanstrategy[w].stack = [
            function (node) {
              meanstrategy['_def-' + w](node);
            }
          ];
        }) (asigner[1]);
      }
    } else {
      (function (w) {
        meanstrategy[w] = function (root) {
          for (var i2 = 0; i2 < meanstrategy[w].stack.length; i2++) {
            if (meanstrategy[w].stack[i2](root)) {
              break;
            }
          }
        }
        meanstrategy[w].stack = [
        ];
      }) (asigner[1]);
    }(function (w, startpoint, stk, transformer, base, anl) {
      for (var i = 0; i < window.meanstrategy[w].stack.length; i++) {
        if (window.meanstrategy[w].stack[i].indentity == base) {
          return;
        }
      }
      window.meanstrategy[w].stack.push(function (noderoot) {
        if (window.meanengine.matcher(stk[startpoint], noderoot, {
        })) {
          if (window.meanengine.run(noderoot, stk, transformer, startpoint, anl)) {
            console.log('Match ln: ' + base);
            return true;
          }
          return false;
        }
        return false;
      });
      window.meanstrategy[w].stack[window.meanstrategy[w].stack.length - 1].indentity = base;
    }) (asigner[1], parseInt(asigner[2]), stack, mstack, baseln, isEnableAnl);
  } else
  for (var i = 0; i < stack.length; i++) {
    if (stack[i].type == 'exact') {
      if (stack[i].word in meanstrategy) {
        if (!meanstrategy[stack[i].word].stack) {
          (function (w) {
            meanstrategy['_def-' + w] = meanstrategy[w];
            meanstrategy[w] = function (root) {
              for (var i2 = 0; i2 < meanstrategy[w].stack.length; i2++) {
                if (meanstrategy[w].stack[i2](root)) {
                  break;
                }
              }
            }
            meanstrategy[w].stack = [
              function (node) {
                meanstrategy['_def-' + w](node);
              }
            ];
          }) (stack[i].word);
        }
      } else {
        (function (w) {
          meanstrategy[w] = function (root) {
            for (var i2 = 0; i2 < meanstrategy[w].stack.length; i2++) {
              if (meanstrategy[w].stack[i2](root)) {
                break;
              }
            }
          }
          meanstrategy[w].stack = [
          ];
        }) (stack[i].word);
      }(function (w, startpoint, stk, transformer, base, anl) {
        for (var i = 0; i < window.meanstrategy[w].stack.length; i++) {
          if (window.meanstrategy[w].stack[i].indentity == base) {
            return;
          }
        }
        window.meanstrategy[w].stack.push(function (noderoot) {
          if (window.meanengine.run(noderoot, stk, transformer, startpoint, anl)) {
            console.log('Match ln: ' + base);
            return true;
          }
          return false;
        });
        window.meanstrategy[w].stack[window.meanstrategy[w].stack.length - 1].indentity = base;
      }) (stack[i].word, i, stack, mstack, baseln, isEnableAnl);
    }
  }
}
meanengine.parsed = {
};
function meanenginelight(ln) {
  var baseln = ln;
  var delim = '=';
  ln = ln.split(delim);
  if (ln.length < 2) {
    console.log(ln + ' không d?y d?, b? qua.');
    return;
  }
  var lefts = ln[0].trim();
  var stack = [
  ];
  var mstack = [
  ];
  var regex = {
    base: /{.*?}/g,
    extend: /^(\d+)(?:\.\.|\~)(\d+)$/,
    exactwidth: /^(\d+)$/,
    havechar: /^\*(.+?)$/,
    haveword: /^\~(.+?)$/,
    lastword: /^:(.+?)$/,
    firstword: /^(.+?):$/,
    mdefault: /{(\d+):}/,
    mtransform: /{(\d+)->(.*?)}/,
    mremove: /{(\d+_?)X}/,
    mshort: /{(\d+)->@}/,
    mappend: /{(\d+)\+(.+?)}/,
    mreplace: /{(\d+)\-(.+?)}/,
    mrepapp: /{(\d+)\-(.+?)\+(.+?)}/,
    mprepend: /{(.+?)\+(\d+)}/,
    mreppre: /{(.+?)\+(\d+)\-(.+?)}/,
    mrepins: /{(.+?)\+(\d+)\-(.+?)\+(.+?)}/,
    minside: /{(.+?)\+(\d+)\+(.+?)}/
  };
  var typing = {
    'N': 'name',
    'PN': 'proname',
    'P': 'pronoun',
    'S': 'number',
    'D': 'deter',
    'D-': 'deter-',
    'SD': 'numdeter',
    'L': 'locat',
    '*L': 'lastlocat',
    'L1': 'locat1',
    'L2': 'locat2',
    'T': 'subw',
    'R': 'relv',
    'R1': 'relv1',
    'R2': 'relv2',
    'R3': 'relv3',
    '*': 'unlim',
    '~': 'unlim',
    'SW': 'stw',
    't:F': 'faction',
    't:I': 'item',
    't:S': 'skill',
    'VI': 'tviet',
    'HV': 'hviet'
  }
  var m;
  var m2;
  lefts = lefts.split(' ');
  for (var i = 0; i < lefts.length; i++) {
    m = lefts[i];
    if (m == '') {
      continue;
    }
    if (m) {
      var token = m;
      if (token in typing) {
        stack.push({
          type: typing[token]
        });
        continue;
      }
      m2 = regex.extend.exec(token);
      if (m2) {
        stack.push({
          type: 'extend',
          min: parseInt(m2[1]),
          max: parseInt(m2[2])
        });
        continue;
      }
      m2 = regex.exactwidth.exec(token);
      if (m2) {
        stack.push({
          type: 'extend',
          min: parseInt(m2[1]),
          max: parseInt(m2[1])
        });
        continue;
      }
      m2 = regex.havechar.exec(token);
      if (m2) {
        stack.push({
          type: 'havechar',
          char: m2[1]
        });
        continue;
      }
      m2 = regex.haveword.exec(token);
      if (m2) {
        stack.push({
          type: 'haveword',
          word: m2[1]
        });
        continue;
      }
      m2 = regex.lastword.exec(token);
      if (m2) {
        stack.push({
          type: 'lastword',
          word: m2[1]
        });
        continue;
      }
      m2 = regex.firstword.exec(token);
      if (m2) {
        stack.push({
          type: 'firstword',
          word: m2[1]
        });
        continue;
      }
      if (m != '>' && m != '<')
      stack.push({
        type: 'exact',
        word: token
      });
    }
  }
  if (lefts[0] == '>') {
    stack[0].isfirst = true;
  }
  if (lefts[lefts.length - 1] == '<') {
    stack[stack.length - 1].islast = true;
  }
  ln.shift();
  var rights = ln.join(delim);
  do {
    m = regex.base.exec(rights);
    if (m) {
      var token = m[0];
      m2 = regex.mshort.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'short'
        });
        continue;
      }
      m2 = regex.mtransform.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'transform',
          word: m2[2]
        });
        continue;
      }
      m2 = regex.mremove.exec(token);
      if (m2) {
        if (m2[1].lastChar() == '_') {
          mstack.push({
            nodeid: parseInt(m2[1]) - 1,
            type: 'removenode'
          });
        } else {
          mstack.push({
            nodeid: parseInt(m2[1]) - 1,
            type: 'remove'
          });
        }
        continue;
      }
      m2 = regex.mappend.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'append',
          word: m2[2]
        });
        continue;
      }
      m2 = regex.mrepapp.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'repapp',
          repword: m2[2],
          word: m2[3]
        });
        continue;
      }
      m2 = regex.mrepins.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[2]) - 1,
          type: 'repins',
          repword: m2[3],
          lword: m2[1],
          rword: m2[4]
        });
        continue;
      }
      m2 = regex.mreppre.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[2]) - 1,
          type: 'reppre',
          repword: m2[3],
          word: m2[1]
        });
        continue;
      }
      m2 = regex.minside.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[2]) - 1,
          type: 'inside',
          lword: m2[1],
          rword: m2[3]
        });
        continue;
      }
      m2 = regex.mprepend.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[2]) - 1,
          type: 'prepend',
          word: m2[1]
        });
        continue;
      }
      m2 = regex.mreplace.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'replace',
          repword: m2[2]
        });
        continue;
      }
      m2 = regex.mdefault.exec(token);
      if (m2) {
        mstack.push({
          nodeid: parseInt(m2[1]) - 1,
          type: 'default'
        });
        continue;
      }
      mstack.push({
        nodeid: parseInt(token.substr(1, token.length - 2)) - 1,
        type: 'retain'
      });
    }
  } while (m != null);
  if (mstack.length < stack.length) {
    for (var i = 0; i < stack.length; i++) {
      var fl = false;
      for (var j = 0; j < mstack.length; j++) {
        if (mstack[j].nodeid == i) {
          fl = true;
          break;
        }
      }
      if (!fl) {
        mstack.push({
          nodeid: i,
          type: 'remove'
        });
      }
    }
  }
  for (var i = 0; i < stack.length; i++) {
    if (stack[i].type == 'exact' && stack[i].word != '?') {
      if (stack[i].word in meanstrategy) {
        if (!meanstrategy[stack[i].word].stack) {
          (function (w) {
            meanstrategy['_def-' + w] = meanstrategy[w];
            meanstrategy[w] = function (root) {
              for (var i2 = 0; i2 < meanstrategy[w].stack.length; i2++) {
                if (meanstrategy[w].stack[i2](root)) {
                  break;
                }
              }
            }
            meanstrategy[w].stack = [
              function (node) {
                meanstrategy['_def-' + w](node);
              }
            ];
          }) (stack[i].word);
        }
      } else {
        (function (w) {
          meanstrategy[w] = function (root) {
            for (var i2 = 0; i2 < meanstrategy[w].stack.length; i2++) {
              if (meanstrategy[w].stack[i2](root)) {
                break;
              }
            }
          }
          meanstrategy[w].stack = [
          ];
        }) (stack[i].word);
      }(function (w, startpoint, stk, transformer, base) {
        for (var i = 0; i < window.meanstrategy[w].stack.length; i++) {
          if (window.meanstrategy[w].stack[i].indentity == base) {
            return;
          }
        }
        window.meanstrategy[w].stack.push(function (noderoot) {
          return window.meanengine.run(noderoot, stk, transformer, startpoint);
        });
        window.meanstrategy[w].stack[window.meanstrategy[w].stack.length - 1].indentity = base;
      }) (stack[i].word, i, stack, mstack, baseln);
    }
  }
}
meanengine.shorthand = function (ln) {
  return ln.replace(/^(>)?(.*?)\+(.*?)=(.*)/, '$1{$2}{*}{$3}->{1->$4}{2}{3}');
}
meanengine.shorthand2 = function (ln) {
  return ln.replace(/^\{0\}(.+?)=\{0\}(.+)/, '{1..2}{$1}->{1}{2->$2}').replace(/^(.+?)\{0\}(.+?)=\{0\}(.+)/, '{$1}{1..2}{$2}->{1X}{2}{3->$3}').replace(/^(.+?)\{0\}=\{0\}(.+)/, '{$1}{1..2}->{2}{1->$2}').replace(/^\{0\}(.+?)=(.+?)\{0\}$/, '{1..2}{$1}->{2->$2}{1}').replace(/^(.+?)\{0\}(.+?)=(.+?)\{0\}$/, '{$1}{1..2}{$2}->{1->$3}{2}{3X}').replace(/^(.+?)\{0\}=(.+?)\{0\}$/, '{$1}{1..2}->{1->$2}{2}').replace(/^\{0\}(.+?)=(.+?)\{0\}(.+)/, '{1..2}{$1}->{$2+1}{2->$3}').replace(/^(.+?)\{0\}(.+?)=(.+?)\{0\}(.+)/, '{$1}{1..2}{$2}->{1->$3}{2}{3->$4}').replace(/^(.+?)\{0\}=(.+?)\{0\}(.+)/, '{$1}{1..2}->{1->$2}{2+$3}').replace(' *', '');
}
meanengine.run = function (root, stack, transform, start, anl) {
  if (anl) {
    prediction.parse(root, function () {
      meanengine.run(root, stack, transform, start);
    });
  }
  var flag = false;
  var cr = start;
  var nodepointer = root;
  var nodel = [
    root
  ];
  if (stack[cr].isfirst) {
    if (nodepointer.isspace(false)) {
      return false;
    }
  }
  if (stack[cr].islast) {
    if (nodepointer.isspace(true)) {
      return false;
    }
  }
  if (start > 0) {
    for (; cr >= 0; cr--) {
      if (stack[cr].isfirst) {
        if (nodepointer.isspace(false)) {
          return false;
        }
      }
      if (stack[cr].islast) {
        if (nodepointer.isspace(true)) {
          return false;
        }
      }
      if (cr > 0 && (stack[cr - 1].type == 'extend' || stack[cr - 1].type == 'unlim')) {
        if (cr - 1 > 0) {
          var rs = meanengine.finder(stack[cr - 2], stack[cr - 1], false, nodepointer);
          if (rs == false) {
            return false;
          }
          nodel.unshift(rs.ins);
          nodel.unshift(rs.found);
          nodepointer = rs.found;
          cr--;
        } else if (stack[cr - 1].isfirst) {
          var rs = meanengine.findend(stack[cr - 1], false, nodepointer);
          if (rs == false) {
            return false;
          }
          nodel.unshift(rs);
          nodepointer = rs[0];
        } else {
          var rs = meanengine.findmax(stack[cr - 1], false, nodepointer);
          if (rs == false) {
            return false;
          }
          nodel.unshift(rs);
          nodepointer = rs[0];
        }
      } else {
        if (cr > 0) {
          var passer = {
          };
          if (nodepointer.pE() && nodepointer.isspace(false) && meanengine.matcher(stack[cr - 1], nodepointer.pE(), passer)) {
            nodel.unshift(passer.grp || nodepointer.pE());
            nodepointer = nodepointer.pE();
          } else {
            return false;
          }
        }
      }
    }
  }
  cr = start;
  nodepointer = root;
  var stmaxidx = stack.length - 1;
  if (start < stack.length - 1) {
    for (; cr <= stmaxidx; cr++) {
      if (stack[cr].isfirst) {
        if (nodepointer.isspace(false)) {
          return false;
        }
      }
      if (stack[cr].islast) {
        if (nodepointer.isspace(true)) {
          return false;
        }
      }
      if (cr < stmaxidx && (stack[cr + 1].type == 'extend' || stack[cr + 1].type == 'unlim')) {
        if (stack[cr + 1].islast) {
          var rs = meanengine.findend(stack[cr + 1], true, nodepointer);
          if (rs == false) {
            return false;
          }
          nodel.push(rs);
          nodepointer = rs[rs.length - 1];
        } else if (cr + 1 < stmaxidx) {
          var rs = meanengine.finder(stack[cr + 2], stack[cr + 1], true, nodepointer);
          if (rs == false) {
            return false;
          }
          nodel.push(rs.ins);
          nodel.push(rs.found);
          nodepointer = rs.found;
          cr++;
        } else {
          var rs = meanengine.findmax(stack[cr + 1], true, nodepointer);
          if (rs == false) {
            return false;
          }
          nodel.push(rs);
          nodepointer = rs[rs.length - 1];
        }
      } else {
        if (cr < stmaxidx) {
          var passer = {
          };
          if (nodepointer.nE() && nodepointer.isspace(true) && meanengine.matcher(stack[cr + 1], nodepointer.nE(), passer)) {
            nodel.push(passer.grp || nodepointer.nE());
            nodepointer = nodepointer.nE();
          } else {
            return false;
          }
        }
      }
    }
  }
  var mct = g(contentcontainer);
  for (var i = 0; i < nodel.length; i++) {
    if (nodel[i].length == 1) {
      nodel[i] = nodel[i][0];
      meanstrategy.highlight(nodel[i], 'ln');
      continue;
    }
    if (nodel[i].length === 0) {
      nodel[i] = document.createElement('i');
      nodel[i].id = 'emp' + i;
    }
    if (nodel[i].push) {
      for (var j = 0; j < nodel[i].length; j++) {
        meanstrategy.highlight(nodel[i][j], 'ln');
      }
    } else {
      meanstrategy.highlight(nodel[i], 'ln');
    }
  }
  var performtrans = [
  ];
  var removedword = [
  ];
  for (var i = 0; i < transform.length; i++) {
    var nodeid = transform[i].nodeid;
    if (nodel[nodeid].length > 0) {
      var b = nodel[nodeid];
      if (transform[i].type == 'default') {
        for (var j = 0; j < b.length; j++) {
          b[j].textContent = getDefaultMean(b[j]);
        }
      } else
      if (transform[i].type == 'remove') {
        for (var j = 0; j < b.length; j++) {
          b[j].textContent = '';
        }
      } else
      if (transform[i].type == 'transform') {
        b[0].textContent = transform[i].word;
        for (var j = 1; j < b.length; j++) {
          b[j].textContent = '';
        }
      } else
      if (transform[i].type == 'append') {
        b[b.length - 1].textContent = getDefaultMean(b[b.length - 1]) + ' ' + transform[i].word;
      } else
      if (transform[i].type == 'prepend') {
        b[0].textContent = transform[i].word + ' ' + getDefaultMean(b[0]);
      } else
      if (transform[i].type == 'replace') {
        var rp = transform[i].repword.split('/');
        rp[0] = new RegExp(rp[0], 'g');
        for (var j = 0; j < b.length; j++) {
          if (rp.length > 1) {
            b[j].textContent = getDefaultMean(b[j]).replace(rp[0], rp[1]);
          } else {
            b[j].textContent = getDefaultMean(b[j]).replace(rp[0], '');
          }
        }
      }
    } else {
      if (transform[i].type == 'removenode') {
        nodel[nodeid].textContent = '';
        nodel[nodeid].cn = '';
        nodel[nodeid].setAttribute('t', '');
      } else
      if (transform[i].type == 'default') {
        if (i == 0 && isUppercase(nodel[0])) {
          nodel[nodeid].textContent = ucFirst(getDefaultMean(nodel[nodeid]));
        } else
        nodel[nodeid].textContent = getDefaultMean(nodel[nodeid]);
      } else
      if (transform[i].type == 'retain') {
        if (i == 0 && isUppercase(nodel[0])) {
          nodel[nodeid].textContent = ucFirst(nodel[nodeid].textContent);
        }
      } else
      if (transform[i].type == 'remove') {
        nodel[nodeid].textContent = '';
      } else
      if (transform[i].type == 'transform') {
        if (i == 0 && isUppercase(nodel[0])) {
          nodel[nodeid].textContent = ucFirst(transform[i].word);
        } else
        nodel[nodeid].textContent = transform[i].word;
      } else
      if (transform[i].type == 'short') {
        nodel[nodeid].textContent = meanengine.getshortform(nodel[nodeid].gT());
      } else
      if (transform[i].type == 'append') {
        transform[i].word = transform[i].word.replace(/\&(\d)/g, function (a, b) {
          return removedword[parseInt(b) - 1]
        });
        if (i == 0 && isUppercase(nodel[0])) {
          nodel[nodeid].textContent = ucFirst(getDefaultMean(nodel[nodeid]) + ' ' + transform[i].word);
        } else
        nodel[nodeid].textContent = getDefaultMean(nodel[nodeid]) + ' ' + transform[i].word;
      } else
      if (transform[i].type == 'prepend') {
        if (i == 0 && isUppercase(nodel[0])) {
          nodel[nodeid].textContent = ucFirst(transform[i].word + ' ' + getDefaultMean(nodel[nodeid]));
        } else
        nodel[nodeid].textContent = transform[i].word + ' ' + getDefaultMean(nodel[nodeid]);
      } else
      if (transform[i].type == 'replace') {
        var rp = transform[i].repword.split('/');
        rp[0] = new RegExp(rp[0], 'g');
        var oldtext = getDefaultMean(nodel[nodeid]);
        if (rp.length > 1) {
          nodel[nodeid].textContent = oldtext.replace(rp[0], rp[1]);
        } else {
          nodel[nodeid].textContent = oldtext.replace(rp[0], '');
          removedword.push(oldtext.replace(nodel[nodeid].textContent, ''));
        }
      } else
      if (transform[i].type == 'reppre') {
        var rp = transform[i].repword.split('/');
        rp[0] = new RegExp(rp[0], 'g');
        var lw = getDefaultMean(nodel[nodeid]);
        if (rp.length > 1) {
          lw = transform[i].word + ' ' + lw.replace(rp[0], rp[1]);
        } else {
          lw = transform[i].word + ' ' + lw.replace(rp[0], '');
        }
        nodel[nodeid].textContent = lw;
      } else
      if (transform[i].type == 'repapp') {
        var rp = transform[i].repword.split('/');
        rp[0] = new RegExp(rp[0], 'g');
        var lw = getDefaultMean(nodel[nodeid]);
        if (rp.length > 1) {
          lw = lw.replace(rp[0], rp[1]) + ' ' + transform[i].word;
        } else {
          lw = lw.replace(rp[0], '') + ' ' + transform[i].word;
        }
        nodel[nodeid].textContent = lw;
      } else
      if (transform[i].type == 'repins') {
        var rp = transform[i].repword.split('/');
        rp[0] = new RegExp(rp[0], 'g');
        var lw = getDefaultMean(nodel[nodeid]);
        if (rp.length > 1) {
          lw = transform[i].lword + ' ' + lw.replace(rp[0], rp[1]) + ' ' + transform[i].rword;
        } else {
          lw = transform[i].lword + ' ' + lw.replace(rp[0], '') + ' ' + transform[i].rword;
        }
        nodel[nodeid].textContent = lw;
      } else
      if (transform[i].type == 'inside') {
        var lw = getDefaultMean(nodel[nodeid]);
        lw = transform[i].lword + ' ' + lw + ' ' + transform[i].rword;
        nodel[nodeid].textContent = lw;
      }
    }
    performtrans.push(nodel[transform[i].nodeid]);
  }
  meanengine.swapper2(nodel, performtrans);
  return true;
}
meanengine.checkparse = function (source) {
  if (source in this.parsed) {
    return true;
  } else {
    this.parsed[source] = true;
    return false;
  }
}
meanengine.db = {
};
meanengine.db.default = [
  '?? PN={1->vì sao}{2}',
  '> {?}{1}[:v]={1->có th?}{2}',
  '> ?={1->nhung}',
  '{?} {1}[:v] {S}={1->liên t?c}{2}{3}',
  '{?} {PN} {?} {1}[:v]={1}{2}{3->}{4}',
  '{?} {1}[:n]={1->ngay c?}{2}',
  '?? ? 1~4 ? <={3}{4->nhu th? nào}',
  '?? * ?? ?={1}{3}{2}{4->mà t?i}',
  '?? * ??={1}{3}{2}',
  '? 1~5 *?={1->tr? l?i tin c?a}{2}',
  '? 1~10 ??={1+?nh hu?ng c?a}{2}',
  '*? * ?? ??={1-gi?ng/don gi?n gi?ng}{2}',
  '*? * ??={1}{2}',
  '*? * ??={thái d?+1}{2}',
  '*? * ??={1}{3}{2}',
  '?? * ??={1->phá k? l?c}{2}',
  '?? * *? <={1}{2}{3-ngoài|bên ngoài|? ngoài}',
  '? * ?={1->gi?ng nhu}{2}',
  ':?? 1~10 ??={1}{2}',
  ':? 1~10 ??={1}{2}',
  '? 1~10 ??={1->gi?ng nhu}{2}',
  '? 1~10 ??={1->gi?ng nhu}{2}',
  '?? 1~6 ??={1->tr? l?i tin c?a}{2}',
  '???? 1~6 ??={1->không có tr? l?i tin c?a}{2}',
  '*? 1~2 ? 1 <={1-d? cho|nhu?ng|nhu?ng|d?|làm cho/b?}{2}{4}',
  '?? 1~6 ???={trong quá trình+1}{2}',
  '?? <={1->có th? th?c hi?n}',
  '> ??={1->sau dó}',
  '> ?? <={1->oh}',
  '@_L:1{SD}{*L}[^trong]={trong+1}{2-trong}',
  '@_L:1{SD}{*L}[^bên trong]={trong+1}{2-bên trong}',
  '@_L:1{SD}{*L}[^trên]={trên+1}{2-trên}',
  '@_L:1{SD}{*L}[^bên trên]={trên+1}{2-bên trên}',
  '?? 1~3 ???={1->là dùng}{2}{3->mà ch? t?o ra}',
  '?? 1~10 *?={1+mi?ng c?a}{2}',
  '??? N ? ??={1+d?i th? c?a}{2}',
  '? N ? ??={1->là d?i th? c?a}{2}',
  '?? 0~2 *?={1->dang}{2}{3}',
  '?? 1~20 ? <={1->dang}{2}',
  '> ? t:F ? <={1->lúc ?}{2}',
  '> ? 1~20 ? <={1->lúc}{2}',
  '> ? 1~20 ?? <={1->lúc}{2}',
  '> ? 1~20 ?? <={1->ch? d?n lúc}{2}',
  '> ? 1~20 ? <={1->ch? d?n lúc}{2}',
  '> ? 1~20 ?? <={1->sau khi}{2}',
  '> ?? 1~20 ? <={1->nhung khi}{2}',
  '> ?? 1~20 ?? <={1->nhung khi}{2}',
  '> ?? 1~20 ? <={1->khi nhìn v?}{2}',
  '> ??? 1~20 ?? <={1->khi chua có}{2}',
  '> ?? 1~20 ??? <={1->m?i khi}{2}',
  '{?} {1}[:v] {1~20} {???}<={1->khi}{2}{3}',
  '{?} {1}[:v] {1~20} {:?} {??}<={1->khi}{2}{3}{4}',
  '*? 1~20 ?? <={1}{2}',
  '*? 1~20 ??? <={1}{2}',
  ':? ~ ? ~ :? 1 <={1}{6}{5}{4}{3}{2}',
  '?? ~ *?={1->ngay c?}{2}{3}',
  '?? 0~2 :?={1->nh?ng}{2}{3-các|nhóm+kia}',
  '? <={1->d?a}',
  '??? <={1->không h?p nhau}',
  '? 1~5 ? ?? <={1->không d?t}{2}{3->trong lòng}',
  'S ??={1}{2->tr? xu?ng}',
  'S ??={1}{2->tr? lên}',
  '?? S={1->dùng hon}{2}',
  '> ??={1->phía trên}',
  '? PN <={1->nhu?ng}{2}',
  '? 1={1->d? cho}{2}',
  '?? ? 1~10 *? 1 <={1}{5}{2}{3:}{4}',
  '???? ? 1~10 *? 1 <={m?t cái+5+duy nh?t}{có th?+3}',
  '?? ?? 1~10 *? 1 <={m?t cái+5}{có th?+3}',
  '???? ?? 1~10 *? 1 <={m?t cái+5+duy nh?t}{có th?+3}',
  '? 1~2 ??={1->t? sâu trong}{2}',
  '? 1~2 ??={1->hu?ng sâu trong}{2}',
  '*? 1~3 ??={1->l?y th?}{2}',
  '?? <={1->h?i}',
  '?? 1~5 ??={1}{2}',
  '?? ~ ??={1}{2}',
  '?? 1~6 *? ??={1->có lo?i c?m giác}{2}{3}',
  'N ?????={1}{2->t? l?a ch?n}',
  '? 1~3 ~??={1+th?c l?c}{2}{3-th?c l?c|c?a}',
  '> ??={1->bình thu?ng}',
  '> ? ? <={1->không}{2->ph?i sao}',
  '? 1~3 ?? <={1->nhu}{2}',
  '*? 0~3 ? ?? <={1}{2}{4->không sai du?c}',
  '? 1~3 ??={1->t?ng d?n}{2}',
  ':? 1~10 ??={1+kh? nang}{2}',
  '?? ? *? 1~99 ? ??={1-nghe/ng?i}{2}{3+mùi v?}{4}',
  '?? *? 1~99 ? ??={1-nghe/ng?i}{2+mùi v?}{3}',
  ':? 1~3 ? ??={1+tru?c m?t}{2}',
  ':? 1~3 ? ??={1->? trên d?nh d?u}{2}',
  ':? 1~3 ? ??={1->? trên d?u}{2}',
  '> ? ~ *?={1->t?ng}{2}{3}',
  '> ? ~ S={1->t?ng}{2}{3}',
  '> ? ~ *?={1->cu?i cùng}{2}{3}',
  '> ? ~ PN={1->luôn}{2}{3}',
  't:F ?? ~??={2->m?t trong t? d?i}{3-m?t trong}{?+1}',
  't:F ?? ~??={2->m?t trong tam d?i}{3-m?t trong}{?+1}',
  't:F ?? ~??={2->m?t trong ngu d?i}{3-m?t trong}{?+1}',
  't:F ?? ~??={2->m?t trong l?c d?i}{3-m?t trong}{?+1}',
  't:F ?? ~??={2->m?t trong th?t d?i}{3-m?t trong}{?+1}',
  't:F ?? ~??={2->m?t trong bát d?i}{3-m?t trong}{?+1}',
  't:F ?? ~??={2->m?t trong c?u d?i}{3-m?t trong}{?+1}',
  't:F ?? ~??={2->m?t trong th?p d?i}{3-m?t trong}{?+1}',
  't:F ?? 1~5 ??={2->m?t trong t? d?i}{3-m?t trong}{4-m?t trong}{?+1}',
  't:F ?? 1~5 ??={2->m?t trong tam d?i}{3-m?t trong}{4-m?t trong}{?+1}',
  't:F ?? 1~5 ??={2->m?t trong ngu d?i}{3-m?t trong}{4-m?t trong}{?+1}',
  't:F ?? 1~5 ??={2->m?t trong l?c d?i}{3-m?t trong}{4-m?t trong}{?+1}',
  't:F ?? 1~5 ??={2->m?t trong th?t d?i}{3-m?t trong}{4-m?t trong}{?+1}',
  't:F ?? 1~5 ??={2->m?t trong bát d?i}{3-m?t trong}{4-m?t trong}{?+1}',
  't:F ?? 1~5 ??={2->m?t trong c?u d?i}{3-m?t trong}{4-m?t trong}{?+1}',
  't:F ?? 1~5 ??={2->m?t trong th?p d?i}{3-m?t trong}{4-m?t trong}{?+1}',
  '?? ~ ~??={1->m?t trong t? d?i}{2-m?t trong}{3-m?t trong}',
  '?? ~ ~??={1->m?t trong tam d?i}{2-m?t trong}{3-m?t trong}',
  '?? ~ ~??={1->m?t trong ngu d?i}{2-m?t trong}{3-m?t trong}',
  '?? ~ ~??={1->m?t trong l?c d?i}{2-m?t trong}{3-m?t trong}',
  '?? ~ ~??={1->m?t trong th?t d?i}{2-m?t trong}{3-m?t trong}',
  '?? ~ ~??={1->m?t trong bát d?i}{2-m?t trong}{3-m?t trong}',
  '?? ~ ~??={1->m?t trong c?u d?i}{2-m?t trong}{3-m?t trong}',
  '?? ~ ~??={1->m?t trong th?p d?i}{2-m?t trong}{3-m?t trong}',
  '?? ~ *? ??={1->m?t co h?i}{2}{3}',
  '?? ~ *? ??={1->hai co h?i}{2}{3}',
  '?? ~ *? ??={1->ba co h?i}{2}{3}',
  '?? ~ *? ??={1->b?n co h?i}{2}{3}',
  '?? ~ *? ??={1->nam co h?i}{2}{3}',
  '?? ~ *? ??={1->sáu co h?i}{2}{3}',
  '?? ~ *? ??={1->b?y co h?i}{2}{3}',
  '?? ~ *? ??={1->tám co h?i}{2}{3}',
  '?? ~ *? ??={1->chín co h?i}{2}{3}',
  '?? ~ *? ??={1->mu?i co h?i}{2}{3}',
  '? 1~3 ?={1->t?i}{2}',
  '? 1~3 ??={1->t?i}{2}',
  '? 1~3 *?={1->hu?ng t?i}{2}{3-t?i}',
  '? 1 ?={1->v?a}{2}{3}',
  '??? 1~3 ??={tru?c+1}{2}',
  ':? :? 1~8 ?={1+trên}{3+này}',
  ':? 1~5 ?={1+trên}{2}',
  ':? :? 1~8 ?={1+trong}{3+này}',
  ':? 1~5 ?? 1~5 ?={1+trong}{4}{5}{3}{2}',
  ':? 1~5 ?={1+trong}{2}{3X}',
  'SD 1~2 ?={trong+1}{2}',
  ':? 1~5 ??={1+trong}{2}{3->}',
  ':? :? 1~8 ?={1+trong}{3+này}',
  ':? 1~5 ?={1+trong}{2}',
  ':? 1~5 ?={1+trong}{2}',
  ':? 1~5 ?={1+trong}{2}',
  ':?? 1~5 ?={1+trong}{2}',
  ':? 1~5 ?={1+trong}{2}',
  ':?? 1~5 ?={1+trong}{2}',
  ':? 1~5 ??={1+trong}{2}',
  ':? 1~5 ?={1+trên}{2}',
  ':?? 1~5 ?={1+trên}{2}',
  ':? 1~5 ?={1+trong}{2}',
  ':? 1~5 ?={1+trong}{2}',
  't:F ?={2->trên}{1}',
  't:F ?={2->trong}{1}',
  't:F ?={2->trong}{1}',
  't:F ?={2->trong}{1}',
  't:F ??={2->bên trong}{1}',
  't:F ??={2->phía trên}{1}',
  'PN ? t:F ? ??={d?a v? c?a+1}{2}{3}',
  'PN ? N ? ??={d?a v? c?a+1}{2}{3}',
  '? 1~3 ?={1->trên}{2}{3->này}',
  '? 1~3 ?={trong+1}{2}{3->này}',
  '? 1~3 ??={1->? trong m?t}{2}',
  'PN ??={2}{1}',
  '? ?? 1~8 ??={1->tru?c khi}{2}{3}',
  '> ? ? <={1->cái}{2->kia}',
  '?? 1~3 ?={1+hon}{2}',
  '??? 1~3 ??={1->gi?ng nhu dang}{2}',
  '? 1~5 ???={1->du?i s? d?n d?u c?a}{2}',
  '?? ~ *????={1->chi?u cao}{2}{3}',
  '? 1~3 *? ?={1->c?n du?ng}{2}{3}',
  '? <={1->a}',
  '> ? S={1->trúng}{2}',
  '?? S={1->l?n này t?n}{2}',
  '?? <={1->không ng?ng}',
  '?? 1 <={1X}{2+nhu v?y}',
  '??? ? 1 <={1-> }{2-> }{3+cao nhu v?y}',
  '?? ? 1 <={1X}{2X}{3+nhu th?}',
  '?? ? 1 <={1X}{2X}{3+nhu v?y}',
  '?? 1 <={1X}{2+nhu th?}',
  '?? 1 <={1X}{2+nhu v?y}',
  '?? 1 ?? *? 1 <={1->có}{5+gi?ng nhu}{2}',
  '?? 1 ?? *?={1->có gi?ng nhu}{2}{3X}{4}',
  '?? 1~3 ?={1->tru?c khi vào}{2}',
  '?? <={1->vô cùng}',
  '?? ~ *? ??={1->dã trúng k? c?a}{2}{3}',
  '?? S={1->cách}{2}',
  '?? t:F={1->cách}{2}',
  '?? ~ ~??={1->du?c}{2}{3}',
  'S ??={2->hon}{1:}',
  'S ???={2->v?a m?i hon}{1:}',
  '?? ~ ? ?={ngu?i+1}{2}',
  '? 1~5 ?={1}{2}',
  'PN ?={1}',
  'S ??={1}{2->ngu?i}',
  '*? ~ ?? <={1}{2}{3->l?i nói}',
  '?? <={1->t?i dây}',
  '?? <={1->dúng v?y}',
  '??? <={1->ti?p t?c di}',
  '?? ?? 1 ??={1->mang theo m?c dích}{3}{2->h?n}',
  '?? 1~8 ??={1->mang theo m?c dích là}{2}',
  '? 1~2 ? 1~2 ?:={1X}{3X}{4+hon}{2}{5->nhi?u}',
  '*? ~ ??={1}{2}{3->d?u s?}',
  'S ? 1~5 ? <={1}{2->là s?}{3}{4->r?i}',
  '> PN ? <={1}{2->nói}',
  'PN :? 1~3 ???={2->sau khi}{1+chia tay}{3}',
  ':? 1~3 ???={1->sau khi chia tay}{2}',
  ':? 1~4 ??={1+trong tay}{2}',
  'PN ??={2}{1}',
  '??: 1~5 ??={1+tr?ng thái}{2}',
  '? 1~3 ???={1->dã bi?n}{2}{3->thành}',
  '?? S ??={1->có hon}{2}',
  '?? S S ??={1->có hon}{2}{3}',
  'PN ? 1~8 ? ? ?:={6->chuy?n}{1}{4}{2->?}{3}{5X}',
  '{S}[nam]{?}={trong+1}{2->}',
  'S ?={1}{2->l?ng}',
  '> ?? PN={1->nhu v?y}{2}',
  ':? ?? ?={1}{2->có chia}{3->thành}',
  '?? 1~5 ??={1->gi?ng nhu là}{2}{3->}',
  '?? 1~2 L={3+nh?ng}{2}{1->này}',
  '?? t:F L={3}{2}{1->này}',
  '? t:F L={3}{2}{1->này}',
  '?? 1~2 ?:={1->nh?ng}{2+kia}{3}',
  '?? 1~2 ?:={1->cái}{2+này}{3}',
  'PN ? ?? 1~2 ? 1 <={6+c?a}{4}{3->này c?a}{1:}',
  '??? ?? ~ ?? ?={1->trong tình hu?ng không có}{3+khác}',
  '??? ~ ?? ?={1->trong tình hu?ng không có}{2}',
  '? ~ ?? ?={1->du?i tình hu?ng}{2}',
  '?? PN ??={1->li?c}{2}{3->m?t cái}',
  '> ?? 1~10 ?? <={1->ngay lúc}{2}',
  '? 1~3 ???={1->không gi?ng v?i}{2}',
  '? ? ?={3->? ta cái này}',
  '? ?={1->?}{2->dó}',
  '? ?={1->ch?}{2->dó}',
  '? ?={1->ch?}{2->này}',
  'PN ??={trong co th? c?a+1}{2X}',
  'PN R1 ??={trong co th? c?a+1}{2X}{3X}',
  'PN ??={co th? c?a+1}{2X}',
  'PN R1 ??={co th? c?a+1}{2X}{3X}',
  '{?} {1}[:v,vn] {0~3} {:?} {PN}={4}{1}{2}{3}',
  ':? ??={1}{2->lúc tru?c}',
  '??? <={1->s? tr? v?}',
  '{?} {1}[:v]={1->d?}{2}',
  '{?} {1}[:i,a]={1->th?t}{2}',
  '{PN} {?}<={1}{2->t?t}',
  '{1}[:uj,v,i,a] {?}={1}{2->d?n}',
  '{???} {1}[:n]={1->nhi?u}{2+nhu v?y}',
  '{???} {R1} {1}[:n]={1->nhi?u}{2}{3+nhu v?y}',
  'P ?? 1 ? 1 <={5}{3}{nhu+1}',
  'P ?? :? 1 <={4}{3}{nhu+1}',
  '> ? <={1->lên}',
  '?? 1 <={1->tr? cho}{2}',
  '?? 1 R<={1->tr? cho}{2}',
  '??? <={1->tr? cho ta}',
  '??? R <={1->tr? cho ta}',
  '{1}[:v,vg,a]{??}={1}{2->ti?p}',
  '{1}[^dang]{?}={1}',
  '{1}[2,]{?}={1}',
  '{1}[:v]{?}={1}{2->n?i}',
  '@_?:0>{:?}{N}={2}{1:}',
  '@_?:2>{1}[:v]{1~2}{:?}{N}={4}{1:}{2}{3}',
  '? 1 ?={1}{2}{3->thêm chút}',
  '? <={1->tri?u}',
  '? <={1->xông}',
  '{?} {1}[^trong|bên trong|trên]={1->}{2+này}'
];
meanengine.db.sdefault = [
  '?? ~ :? 1={4}{1:}{2}{3}',
  '> PN 1 2~10 L ?={5}{1:}{2}{4}{3}',
  '> PN 3~10 ?={3}{1:}{2}',
  '{?} {1} {1}[:v]={1X}{3-(d?n|vào|t?i|qua|lên|xu?ng|v?).*}{2+&1}',
  '{?}[t?i] {1} {1}<={1X}{3-t?i}{2+t?i}',
  '{?}[d?n] {1} {1}<={1X}{3-d?n}{2+d?n}',
  '{?}[lên] {1} {1}<={1X}{3-lên}{2+lên}',
  '{?}[xu?ng] {1} {1}<={1X}{3-xu?ng}{2+xu?ng}',
  '{?}[qua] {1} {1}<={1X}{3-qua}{2+qua}',
  '?? 1 1 <={3-t?i|hu?ng|v?+v?}{1X}{2}',
  '? 1 1 <={3-t?i|hu?ng|v?+v?}{1X}{2}',
  '?? 1 <={1X}{2+gì}',
  '{??} {1}[:n] {1}[:n]={1X}{2}{3+gì}',
  '{?} {PN} {?} {1}[:n,d]={4}{3}{1}{2}',
  '{?} {PN} {1}[:v] {?} {1}[:n,d]={5}{4}{3}{1}{2}',
  '{1}[:a]{?}{1}[:n,a]<={2:}{3}{1:}',
  '{1}[:a]{?}{1}[:n,a] {1}[:d,v,ad,p,u]={2:}{3}{1:}',
  '>{PN}{?}{1}<={3+c?a}{1:}{2_X}',
  '>{1}[:c,a]{PN}{?}{1}<={1}{4+c?a}{2:}{3_X}',
  '>{PN}{?}{1}[:n]{1}[:v,d,ad,p,u]={3+c?a}{1:}{4}',
  '>{PN}{?}{1}{?:}={3+c?a}{1}{4:}{2_X}',
  '{PN}{?}{1}{*?}{PN}{?}{1}={3+c?a}{1}{4->và}{7+c?a}{5}',
  '{??} {PN} {?} {1} <={1}{4}{2}{3}',
  '{??} {1} {?} {1} <={4}{1}{2}{3}',
  '{:?} {PN} {?} {1} <={1}{4+c?a}{2}{3_X}',
  '{?} {PN} {:?}[1,3] {1} <={1}{4+c?a}{2}{3}',
  '{?} {PN} {:?}[4,] {1} <={1}{4}{2}{3}',
  '{?} {:?} {1} <={1}{3}{2}',
  '{:?} {PN} {PN} {?} {1} <={1}{5+c?a}{4_X}{3}{2}',
  '{PN} {1}{?} {1}[:v]={1}{2}{3->mà}{4}',
  '{PN} {1}[:v]{?} {1}[:n] {1}[:n]<={5}{4}{1}{2}',
  '{PN} {1}[:v]{?} {1}[:n]<={4}{1}{2}',
  '{PN} {1}[:n,a,i,m]{?} {1}[:n] {1}[:n]<={5}{4}{2}{1}',
  '{PN} {1}[:n,a,i,m]{?} {1}[:n]<={4}{2}{1}',
  '{PN} {1}[:v]{?} {1}[:n] {1}[:n] {SW}={5}{4}{1}{2}{6}',
  '{PN} {1}[:v]{?} {1}[:n] {SW}={4}{1}{2}{5}',
  '{PN} {1}[:n,a,i,m]{?} {1}[:n] {1}[:n] {SW}={5}{4}{2}{1}{6}',
  '{PN} {1}[:n,a,i,m]{?} {1}[:n] {SW}={4}{2}{1}{5}',
  '{PN} {?} {1}[^trong|ngoài|trên|du?i]={3+c?a}{1}',
  '{PN} {?} {1} {1}[^trong|ngoài|trên|du?i]={4}{3+c?a}{1}',
  '@_?:1>{PN} {:?}[:v] {1}[này]<={3-này}{1}{2+này}',
  '@_?:1>{PN} {:?}[:v] {1}[:v]<={1}{2}{3}',
  '@_?:1>{PN} {:?}[:v] {1}[:n]<={3}{1}{2}',
  '@_?:1>{PN} {:?}[3,] {1}[:c]<={1}{2}{3}',
  '@_?:1>{PN} {:?}[3,] {1}[:v]<={1}{3}{2}',
  '@_?:1>{PN} {:?}[3,] {1}<={3}{2+c?a}{1}',
  '@_?:1>{PN} {:?}[:v] {1}[này] {SW}={3-này}{1}{2+này}{4}',
  '@_?:1>{PN} {:?}[:v] {1}[:n] {SW}={3}{1}{2}{4}',
  '@_?:1>{PN} {:?}[3,] {1}[:n] {SW}={3}{2+c?a}{1}{4}',
  '@_?:1>{PN} {:?}[3,] {1}[:n]<={3}{2+c?a}{1}',
  '@_?:1>{PN} {:?}[3,] {1}[:n] {SW}={3}{2+c?a}{1}{4}',
  '{:?} {PN} {?} {1} <={4}{2}{3_X}{1->này}',
  '{?} {PN} {:?} {1} <={4}{2}{3_X}{1->này}',
  '{?} {:?} {1} <={3}{2}{1->này}',
  '{:?} {PN} {PN} {?} {1} <={5}{4_X}{3}{2}{1->này}',
  '{:?} {1} {PN} {?} {1} <={1}{2}{5}{4_X}{3}',
  '{:?} {1} {t:F} {?} {1} <={1}{2}{5}{4_X}{3}',
  '{?:?} {0~1} {L} {?} {1} <={5}{1}{2}{3}{4_X}',
  '{?:} {~} {:?} {~} {L} {?} {1} <={7}{1}{2}{3}{4}{5}{6}',
  '{?}{SD}{1~2}{?}{VI}={5}{2}{3}{1->này}{4}',
  '{?}{SD}{1~2}{?}{1}<={5}{2}{3}{1->này}{4}',
  '{?}{SD}{:?}{VI}={4}{2}{3}{1->này}',
  '{?}{SD}{:?}{1}<={4}{2}{3}{1->này}',
  '{?}{SD}{1~2}{?}{VI}={2}{5}{3}{1->kia}{4}',
  '{?}{SD}{1~2}{?}{1}<={2}{5}{3}{1->kia}{4}',
  '{?}{SD}{:?}{VI}={2}{4}{3}{1->kia}',
  '{?}{SD}{:?}{1}<={2}{4}{3}{1->kia}',
  '{SD}{1~2}{?}{VI}={1}{4}{2}{3}',
  '{SD}{1~2}{?}{1}<={1}{4}{2}{3}',
  '{SD}{:?}{VI}={1}{3}{2}',
  '{SD}{:?}{1}<={1}{3}{2}',
  '{?}{D}{1~2}{?}{VI}={2}{5}{3}{1->này}{4}',
  '{?}{D}{1~2}{?}{1}<={2}{5}{3}{1->này}{4}',
  '{?}{D}{:?}{VI}={2}{4}{3}{1->này}',
  '{?}{D}{:?}{1}<={2}{4}{3}{1->này}',
  '{?}{D}{1~2}{?}{VI}={2}{5}{3}{1->kia}{4}',
  '{?}{D}{1~2}{?}{1}<={2}{5}{3}{1->kia}{4}',
  '{?}{D}{:?}{VI}={2}{4}{3}{1->kia}',
  '{?}{D}{:?}{1}<={2}{4}{3}{1->kia}',
  '{D-}[1]{1~2}{?}{VI}={4}{2}{3}{1-cái}',
  '{D-}[1]{1~2}{?}{1}<={4}{2}{3}{1-cái}',
  '{D-}[1]{:?}{VI}={3}{2}{1-cái}',
  '{D-}[1]{:?}{1}<={3}{2}{1-cái}',
  '{D}{1~2}{?}{VI}={1}{4}{2}{3}',
  '{D}{1~2}{?}{1}<={1}{4}{2}{3}',
  '{D}{:?}{VI}={1}{3}{2}',
  '{D}{:?}{1}<={1}{3}{2}',
  '{?}{S}{D}{1~2}{?}{VI}={6}{2}{3}{4}{1->này}{5}',
  '{?}{S}{D}{1~2}{?}{1}<={6}{2}{3}{4}{1->này}{5}',
  '{?}{S}{D}{:?}{VI}={5}{2}{3}{4}{1->này}',
  '{?}{S}{D}{:?}{1}<={5}{2}{3}{4}{1->này}',
  '{?}{S}{D}{1~2}{?}{VI}={2}{3}{6}{4}{1->kia}{5}',
  '{?}{S}{D}{1~2}{?}{1}<={2}{3}{6}{4}{1->kia}{5}',
  '{?}{S}{D}{:?}{VI}={2}{3}{5}{4}{1->kia}',
  '{?}{S}{D}{:?}{1}<={2}{3}{5}{4}{1->kia}',
  '{S}{D}{1~2}{?}{VI}={1}{2}{5}{3}{4}',
  '{S}{D}{1~2}{?}{1}<={1}{2}{5}{3}{4}',
  '{S}{D}{:?}{VI}={1}{2}{4}{3}',
  '{S}{D}{:?}{1}<={1}{2}{4}{3}',
  '{PN}{?}{1~2}{?}{VI}={5}{3}{2->này c?a}{1:}{4}',
  '{PN}{?}{1~2}{?}{1}<={5}{3}{2->này c?a}{1:}',
  '{PN}{?}{:?}{VI}={4}{3}{2->này c?a}{1:}',
  '{PN}{?}{:?}{1}<={4}{3}{2->này c?a}{1:}',
  '{?}{1~2}{?}{VI}={4}{2}{1->này}{3}',
  '{?}{1~2}{?}{1}<={4}{2}{1->này}{3}',
  '{?}{:?}{VI}={3}{2}{1->này}',
  '{?}{:?}{1}<={3}{2}{1->này}',
  '{PN}{?}{1~2}{?}{VI}={5}{3}{2->kia c?a}{1:}{4}',
  '{PN}{?}{1~2}{?}{1}<={5}{3}{2->kia c?a}{1:}',
  '{PN}{?}{:?}{VI}={4}{3}{2->kia c?a}{1:}',
  '{PN}{?}{:?}{1}<={4}{3}{2->kia c?a}{1:}',
  '{?}{1~2}{?}{VI}={4}{2}{1->kia}{3}',
  '{?}{1~2}{?}{1}<={4}{2}{1->kia}{3}',
  '{?}{:?}{VI}={3}{2}{1->kia}',
  '{?}{:?}{1}<={3}{2}{1->kia}',
  '? 1~3 L1={1}{3-phía|bên}{2}',
  '{?}{SD}{VI}={2}{3}{1->này}',
  '{?}{SD}{1}<={2}{3}{1->này}',
  '{?}{SD}{VI}={2}{3}{1->kia}',
  '{?}{SD}{1}<={2}{3}{1->kia}',
  '{?}{S}{D}{VI}={2}{3}{4}{1->này}',
  '{?}{S}{D}{1}<={2}{3}{4}{1->này}',
  '{?}{S}{D}{VI}={2}{3}{4}{1->kia}',
  '{?}{S}{D}{1}<={2}{3}{4}{1->kia}',
  '{?}{D}{VI}={2}{3}{1->này}',
  '{?}{D}{1}<={2}{3}{1->này}',
  '{?}{D}{VI}={2}{3}{1->kia}',
  '{?}{D}{1}<={2}{3}{1->kia}',
  '? D ~ :? 1 <={1}{2}{5}{3}{4}',
  '? SD ~ :? 1 <={1}{2}{5}{3}{4}',
  '? ~ :? 1 <={1}{4}{2}{3}',
  '? ~ :? 1 SW={1}{4}{2}{3}{5}',
  '??? ~ :? 1 SW={1->t?i sao}{4}{2:}{3:}{5:}',
  '?? ~ :? 1 SW={1X}{4}{2}{3+gì}{5}',
  '>{1}[trong|tru?c|ngoài|trên|du?i|c?nh]{?}{1}{SW}={3}{1}{4}',
  '{*L}[3,]{?}{1}{SW}={3}{1}{4}',
  '{SW}{1~3}{L}{?}{1~2}{SW}={1}{5}{3}{2}{6}',
  '? 1~2 ??={1->t?t hon}{2}',
  '? 1~2 ?? 1 ??={4}{1->t?t hon}{2}{3X}{5}',
  '? 1~2 ?? 1 <={4}{1->t?t hon}{2}',
  '? 1~2 ?? 1 <={1->còn d?}{4+hon}{2}',
  '? ?: ? 1={1->còn}{4+hon}{2}',
  '? ? 1 ? 1={1->còn}{5+hon}{3}{2->này}',
  '? 1~2 :? ??={1X}{3+hon}{2}{4->không ít}',
  '? {PN} 1 ?={3+hon}{2}',
  'SD 1~5 ??? 1={1}{4}{2}',
  'D 1~5 ??? 1={1}{4}{2}',
  '@_?:0{?:?}[4,10]{*L}[trong|tru?c|ngoài|trên|du?i|c?nh]{1}={2:}{1:}',
  '@_?:0{?:?}[kia|dó]{1}<={2+c?a}{1}',
  '@_?:0>{:?}[trong|tru?c|ngoài|trên|du?i|c?nh]{1}{SW}={2}{1}{3}',
  '? PN 1 <={3}{2}{1_X}',
  '{?}{PN}{L}={3}{2}{1->này}',
  '{?}{PN}{L}={3}{2}{1->kia}',
  '{?}{PN}={2}{1->này}',
  '{?}{PN}={2}{1->kia}',
  '? 1~3 L={3}{2}{1->kia}',
  '? 1~3 L={3}{2}{1->này}',
  '{?} {1}[:n] {SW}={2}{1->này}{3}',
  '{?} {1}[:n] {1}[:n] {SW}={2}{1->này}{3}',
  '{?} {1}[:i,a] {VI}[:n] {1}[:n]={4}{3}{2}{1->này}',
  '{?} {1}[:i,a] {1}[:n] {VI}[:n]={4}{3}{2}{1->này}',
  '{?} {1}[:i,a] {1}[:n] {1}[:n]={3}{4}{2}{1->này}',
  '{?} {1}[:i,a] {1}[:n]={3}{2}{1->này}',
  '{?} {1}[:i,a] {?} {VI}[:n] {1}[:n]={5}{4}{2}{1->này}',
  '{?} {1}[:i,a] {?} {1}[:n] {VI}[:n]={5}{4}{2}{1->này}',
  '{?} {1}[:i,a] {?} {1}[:n] {1}[:n]={5}{4}{2}{1->này}',
  '{?} {1}[:i,a] {?} {1}[:n]={4}{2}{1->này}',
  '{??} {1}[:n] {SW}={2}{1->này}{3}',
  '{??} {1}[:n] {1}[:n] {SW}={2}{1->này}{3}',
  '{??} {1}[:i,a] {VI}[:n] {1}[:n]={4}{3}{2}{1->này}',
  '{??} {1}[:i,a] {1}[:n] {VI}[:n]={4}{3}{2}{1->này}',
  '{??} {1}[:i,a] {1}[:n] {1}[:n]={3}{4}{2}{1->này}',
  '{??} {1}[:i,a] {1}[:n]={3}{2}{1->này}',
  '{??} {1}[:i,a] {?} {VI}[:n] {1}[:n]={5}{4}{2}{1->này}',
  '{??} {1}[:i,a] {?} {1}[:n] {VI}[:n]={5}{4}{2}{1->này}',
  '{??} {1}[:i,a] {?} {1}[:n] {1}[:n]={5}{4}{2}{1->này}',
  '{??} {1}[:i,a] {?} {1}[:n]={4}{2}{1->này}',
  '{?} {1}[:n] {SW}={2}{1->này}{3}',
  '{?} {1}[:n] {1}[:n] {SW}={2}{1->kia}{3}',
  '{?} {1}[:i,a] {VI}[:n] {1}[:n]={4}{3}{2}{1->kia}',
  '{?} {1}[:i,a] {1}[:n] {VI}[:n]={4}{3}{2}{1->kia}',
  '{?} {1}[:i,a] {1}[:n] {1}[:n]={3}{4}{2}{1->kia}',
  '{?} {1}[:i,a] {1}[:n]={3}{2}{1->kia}',
  '{?} {1}[:i,a] {?} {VI}[:n] {1}[:n]={5}{4}{2}{1->kia}',
  '{?} {1}[:i,a] {?} {1}[:n] {VI}[:n]={5}{4}{2}{1->kia}',
  '{?} {1}[:i,a] {?} {1}[:n] {1}[:n]={5}{4}{2}{1->kia}',
  '{?} {1}[:i,a] {?} {1}[:n]={4}{2}{1->kia}',
  '{??} {1}[:n] {SW}={2}{1->kia}{3}',
  '{??} {1}[:n] {1}[:n] {SW}={2}{1->kia}{3}',
  '{??} {1}[:i,a] {VI}[:n] {1}[:n]={4}{3}{2}{1->kia}',
  '{??} {1}[:i,a] {1}[:n] {VI}[:n]={4}{3}{2}{1->kia}',
  '{??} {1}[:i,a] {1}[:n] {1}[:n]={3}{4}{2}{1->kia}',
  '{??} {1}[:i,a] {1}[:n]={3}{2}{1->kia}',
  '{??} {1}[:i,a] {?} {VI}[:n] {1}[:n]={5}{4}{2}{1->kia}',
  '{??} {1}[:i,a] {?} {1}[:n] {VI}[:n]={5}{4}{2}{1->kia}',
  '{??} {1}[:i,a] {?} {1}[:n] {1}[:n]={5}{4}{2}{1->kia}',
  '{??} {1}[:i,a] {?} {1}[:n]={4}{2}{1->kia}'
];
meanengine.db.tokenfind = {
  'deter': arrtoobj(['?',
  '??',
  '?',
  '??',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '??',
  '??',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?']),
  'deter-': [
    '?',
    '??',
    '?',
    '??',
    '?',
    '??',
    '??'
  ],
  'locat': [
    '?',
    '?',
    '?',
    '?',
    '?',
    '?',
    '?',
    '?',
    '?',
    '?',
    '?',
    '?',
    '??',
    '??',
    '??',
    '??',
    '??',
    '??',
    '??',
    '??',
    '??',
    '??',
    '??'
  ],
  'locat1': [
    '?',
    '?',
    '??',
    '??'
  ],
  'locat2': [
    '?',
    '?',
    '?',
    '?',
    '?',
    '??',
    '??',
    '??',
    '??'
  ],
  'subw': [
    '?',
    '?',
    '?',
    '?',
    '?',
    '?'
  ],
  'relv': [
    '?',
    '?',
    '?'
  ],
  'relv1': [
    '?'
  ],
  'relv2': [
    '?'
  ],
  'relv3': [
    '?'
  ],
  'cc': [
    '??',
    '?',
    '?',
    '?'
  ],
  'stwd': arrtoobj(['?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?']),
  'pronoun': arrtoobj(['?',
  '???',
  '?',
  '?',
  '?',
  '?',
  '?',
  '?',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '??',
  '???',
  '??',
  '???',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '?',
  '??',
  '??',
  '??',
  '?',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '?',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '??',
  '??',
  '??',
  '??',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '?',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '?',
  '??',
  '??',
  '??',
  '?',
  '??',
  '?',
  '??',
  '??',
  '??',
  '??',
  '?',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '?',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '?',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '?',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '?',
  '??',
  '??',
  '??',
  '???',
  '???',
  '??',
  '??',
  '??',
  '??',
  '???',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '???',
  '???',
  '??',
  '???',
  '??',
  '???',
  '???',
  '???',
  '???',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??',
  '??'])
}
meanengine.matcher = function (find, node, passed) {
  if (find.modifier) {
    if (!meanengine.modifier(node, find.modifier)) {
      return false;
    }
  }
  if (find.type == 'extend') {
    if (find.isfirst) {
      return !node.isspace(false);
    }
    if (find.islast) {
      return !node.isspace(true);
    }
    return true;
  }
  if (find.type == 'exact') {
    return node.gT() == find.word;
  }
  if (find.type in meanengine.db.tokenfind) {
    return meanengine.db.tokenfind[find.type].indexOf(node.gT()) >= 0;
  }
  if (find.type == 'lastlocat') {
    return meanengine.db.tokenfind.locat.indexOf(node.gT().lastChar()) >= 0;
  }
  if (find.type == 'name') {
    return node.containName();
  }
  if (find.type == 'proname') {
    if (node.containName() || meanengine.db.tokenfind.pronoun.indexOf(node.gT()) >= 0) {
      return true;
    }
    return false;
  }
  if (find.type == 'number') {
    return meanstrategy.containnumber(node);
  }
  if (find.type == 'faction') {
    return meanengine.wordIsFaction(node, passed);
  }
  if (find.type == 'havechar') {
    var a = node.gT();
    for (var i = 0; i < a.length; i++) {
      if (find.char.indexOf(a[i]) >= 0) {
        return true;
      }
    }
    return false;
  }
  if (find.type == 'haveword') {
    return node.gT().indexOf(find.word) >= 0;
  }
  if (find.type == 'lastword') {
    return node.gT().indexOf(find.word, node.gT().length - find.word.length) !== - 1;
  }
  if (find.type == 'stw') {
    var a = node.gT();
    for (var i = 0; i < a.length; i++) {
      if (meanengine.db.tokenfind.stwd.indexOf(a[i]) >= 0) {
        return true;
      }
    }
    return false;
  }
  if (find.type == 'firstlast') {
    return (node.gT().indexOf(find.word1) === 0) && (node.gT().indexOf(find.word2, node.gT().length - find.word2.length) !== - 1);
  }
  if (find.type == 'firstword') {
    return node.gT().indexOf(find.word) === 0;
  }
  if (find.type == 'numdeter') {
    return meanstrategy.containnumber(node) && meanengine.db.tokenfind['deter'].indexOf(node.gT().lastChar()) >= 0;
  }
  if (find.type == 'tviet') {
    var m = node.getAttribute('v');
    if (m) {
      m = m.toLowerCase().split('/');
      var h = node.gH();
      if (h == '') {
        return false;
      }
      var hc = 0;
      for (var c = 0; c < m.length; c++) {
        if (h == m[c]) {
          hc++;
        }
      }
      return hc / m.length < 0.4;
    }
  }
  return false;
}
meanengine.modifier = function (node, mod) {
  if (mod.type == 'have') {
    return new RegExp(mod.text).test(node.textContent);
  }
  if (mod.type == 'length') {
    var l = node.gT().length;
    if (l > mod.max || l < mod.min) {
      return false;
    }
    return true;
  }
  if (mod.type == 'pos') {
    var pl = mod.postype.split(',');
    var np = node.getAttribute('p');
    for (var i = 0; i < pl.length; i++) {
      if (pl[i] == np) {
        return true;
      }
    }
    return false;
  }
}
meanengine.finder = function (tofind, step, dir, node) {
  var ndin = [
  ];
  if (step.type == 'unlim') {
    step.max = 999;
    step.min = 0;
  }
  var grp = {
  };
  if (dir) {
    for (var i = 0; i <= step.max; i++) {
      node = node.nE();
      if (node == null || !node.isspace(false)) return false;
      if (i < step.min) {
        if (step.modifier) {
          if (!this.modifier(node, step.modifier)) {
            return false;
          }
        }
        ndin.push(node);
        continue;
      }
      if (meanengine.matcher(tofind, node, grp)) {
        return {
          ins: ndin,
          found: grp.grp || node
        };
      }
      ndin.push(node);
    }
  } else {
    for (var i = 0; i <= step.max; i++) {
      node = node.pE();
      if (node == null || !node.isspace(true)) return false;
      if (i < step.min) {
        if (step.modifier) {
          if (!this.modifier(node, step.modifier)) {
            return false;
          }
        }
        ndin.unshift(node);
        continue;
      }
      if (meanengine.matcher(tofind, node, grp)) {
        return {
          ins: ndin,
          found: grp.grp || node
        };
      }
      ndin.unshift(node);
    }
  }
  return false;
}
meanengine.findend = function (step, dir, node) {
  var ndin = [
  ];
  if (step.type == 'unlim') {
    step.max = 999;
    step.min = 0;
  }
  if (dir) {
    for (var i = 0; i <= step.max; i++) {
      node = node.nE();
      if (node == null || !node.isspace(false)) return i >= step.min ? ndin : false;
      if (step.modifier) {
        if (!this.modifier(node, step.modifier)) {
          return i >= step.min ? ndin : false;
        }
      }
      ndin.push(node);
      continue;
    }
  } else {
    for (var i = 0; i <= step.max; i++) {
      node = node.pE();
      if (node == null || !node.isspace(true)) return i >= step.min ? ndin : false;
      if (step.modifier) {
        if (!this.modifier(node, step.modifier)) {
          return i >= step.min ? ndin : false;
        }
      }
      ndin.unshift(node);
    }
  }
  return false;
}
meanengine.findmax = function (step, dir, node) {
  var ndin = [
  ];
  if (step.type == 'unlim') {
    step.max = 999;
    step.min = 0;
  }
  if (dir) {
    for (var i = 0; i <= step.max; i++) {
      node = node.nE();
      if (node == null || !node.isspace(false) || i == step.min) return i >= step.min ? ndin : false;
      if (step.modifier) {
        if (!this.modifier(node, step.modifier)) {
          return i >= step.min ? ndin : false;
        }
      }
      ndin.push(node);
    }
  } else {
    for (var i = 0; i <= step.max; i++) {
      node = node.pE();
      if (node == null || !node.isspace(true) || i == step.min) return i >= step.min ? ndin : false;
      if (step.modifier) {
        if (!this.modifier(node, step.modifier)) {
          return i >= step.min ? ndin : false;
        }
      }
      ndin.unshift(node);
    }
  }
  return false;
}
meanengine.swapper = function (froms, tos) {
  var ctn = froms[0].parentElement || froms[0][0].parentElement;
  var ida,
  idb;
  for (var i = 0; i < froms.length; i++) {
    idb = tos[i].id || tos[i][0].id;
    ida = froms[i].id || froms[i][0].id;
    if (ida != idb) {
      if (froms[i].push) {
        if (tos[i].push) {
          for (var j = 0; j < tos[i].length; j++) {
            ctn.insertBefore(document.createTextNode(' '), froms[i][0]);
            ctn.insertBefore(tos[i][j], froms[i][0]);
          }
        } else {
          ctn.insertBefore(document.createTextNode(' '), froms[i][0]);
          ctn.insertBefore(tos[i], froms[i][0]);
        }
      } else {
        if (tos[i].push) {
          for (var j = 0; j < tos[i].length; j++) {
            ctn.insertBefore(document.createTextNode(' '), froms[i]);
            ctn.insertBefore(tos[i][j], froms[i]);
          }
        } else {
          ctn.insertBefore(document.createTextNode(' '), froms[i]);
          ctn.insertBefore(tos[i], froms[i]);
        }
      }
      if (i < froms.length - 1) {
        var tmp = froms[i];
        froms[i] = froms[i + 1];
        froms[i + 1] = tmp;
        if (froms[i + 1].push) {
          if (froms[i + 1][0].previousSibling && froms[i + 1][0].previousSibling.nodeType != 3) {
            ctn.insertBefore(document.createTextNode(' '), froms[i + 1][0]);
          }
        } else
        if (froms[i + 1].previousSibling && froms[i + 1].previousSibling.nodeType != 3) {
          ctn.insertBefore(document.createTextNode(' '), froms[i + 1]);
        }
      }
    }
  }
  if (tos[0].push) {
    tos[0][0].tomean(tos[0][0].textContent);
  } else
  tos[0].tomean(tos[0].innerHTML);
}
meanengine.swapper2 = function (froms, tos) {
  var flag = false;
  for (var i = 0; i < froms.length; i++) {
    if (tos[i] != froms[i]) {
      flag = true;
      break;
    }
  }
  if (!flag) {
    return;
  }
  var ctn = froms[0].parentElement || froms[0][0].parentElement;
  var lastn = froms[froms.length - 1].nextSibling;
  for (var i = 0; i < froms.length; i++) {
    if (froms[i].push) {
      for (var j = 0; j < froms[i].length; j++) {
        if (froms[i][j].isspace(false)) {
          froms[i][j].previousSibling.remove();
        }
        froms[i][j].remove();
      }
    } else {
      if (froms[i].isspace(false)) {
        froms[i].previousSibling.remove();
      }
      froms[i].remove();
    }
  }
  var tn;
  for (var i = tos.length - 1; i >= 0; i--) {
    if (tos[i].push) {
      for (var j = tos[i].length - 1; j >= 0; j--) {
        tn = document.createTextNode(' ');
        ctn.insertBefore(tos[i][j], lastn);
        ctn.insertBefore(tn, tos[i][j]);
        lastn = tn;
      }
    } else {
      tn = document.createTextNode(' ');
      ctn.insertBefore(tos[i], lastn);
      ctn.insertBefore(tn, tos[i]);
      lastn = tn;
    }
  }
  if (tos[0].push) {
    tos[0][0].tomean(tos[0][0].textContent);
  } else
  tos[0].tomean(tos[0].textContent);
}
meanengine.swapgt = function (node1, node2) {
  var tmp = node1.gT();
  var tmp2 = node1.getAttribute('v');
  node1.setAttribute('t', node2.gT());
  node1.cn = node2.gT();
  node1.setAttribute('v', node2.getAttribute('v'));
  node2.setAttribute('t', tmp);
  node2.cn = tmp;
  node2.setAttribute('v', tmp2);
}
meanengine.swapm = function (node1, node2) {
  var tmp = node1.innerHTML;
  node1.setmean(node2.textContent);
  node2.setmean(tmp);
}
meanengine.cleantext = function () {
  var str = g(contentcontainer).innerHTML;
  str = str.replace(/d?o ?<\/i>:/g, 'nói</i>:');
  str = str.replace(/&nbsp;&nbsp;&nbsp;&nbsp;/g, '<br>');
  str = str.replace(/ ”/g, '”');
  str = str.replace(/ ([,\.!\?”]+)/g, '$1').replace(/ +<\/i>([ ,\.”\?\!])/g, '</i>$1');
  g(contentcontainer).textContent = str;
}
meanengine.usedefault = function () {
  for (var i = 0; i < this.db.default.length; i++) {
    meanengine(this.db.default[i]);
  }
  if (window.setting.enabletestln) {
    for (var i = 0; i < this.db.sdefault.length; i++) {
      meanengine(this.db.sdefault[i]);
    }
  }
}
meanengine.wordIsFaction = function (node, passed) {
  if (meanstrategy.recognized[node.id] && meanstrategy.recognized[node.id].type == 'faction') {
    var rgobj = meanstrategy.recognized[node.id];
    passed.grp = rgobj.range;
    return true;
  } else if (node.textContent.toLowerCase() != node.textContent && node.textContent.split(' ').length > 1) {
    return meanstrategy.factions.indexOf(node.gT().lastChar()) >= 0;
  }
}
var analyzer = {
  add: function (chi, num) {
    this.data[chi] = num;
  },
  load: function () {
    var s = store.getItem('a' + abookhost + abookid);
    if (s == null) {
      return;
    }
    s = s.split('&');
    this.readed = s[0].split(';');
    dat = s[1].split(';');
    dat.forEach(function (it) {
      if (it != '') {
        var a = it.split('=');
        analyzer.data[a[0]] = parseInt(a[1]);
      }
    });
  },
  g: function (chi) {
    return this.data[chi];
  },
  update: function (chi, num) {
    if (this.readed.indexOf(abookchapter) < 0) {
      if (chi in this.data) {
        this.data[chi] += num;
      } else {
        this.data[chi] = num;
      }
    } else if (!(chi in this.data)) {
      this.data[chi] = num;
    }
  },
  readthis: function () {
    if (this.readed.indexOf(abookchapter) < 0) {
      this.readed.push(abookchapter);
    }
  },
  readed: [
  ],
  data: {
  },
  addedname: {
  },
  collected: {
  },
  tocollect: [
  ],
  collectphrase: function (node) {
    try {
      var chitext = node.gT();
      var vitext = node.textContent;
      var hvtext = node.gH();
      var multext = node.getAttribute('v');
      var nd = node;
      while (nd.isspace(true)) {
        nd = nd.nE();
        chitext += nd.gT();
        vitext += ' ' + nd.textContent;
        hvtext += ' ' + nd.gH();
        multext += ' ' + nd.getAttribute('v');
      }
      nd = node;
      while (nd.isspace(false)) {
        nd = nd.pE();
        chitext = nd.gT() + chitext;
        vitext = nd.textContent + ' ' + vitext;
        hvtext = nd.gH() + ' ' + hvtext;
        multext = nd.getAttribute('v') + ' ' + multext;
      }
      if (chitext in this.collected) {
        return;
      } else {
        this.collected[chitext] = true;
      }
      ajax('ajax=collectphrase&chi=' + encodeURIComponent(chitext)
      + '&vi=' + encodeURIComponent(vitext)
      + '&hv=' + encodeURIComponent(hvtext)
      + '&mul=' + encodeURIComponent(multext), function () {
        console.log('Collected ' + chitext);
      });
    } catch (x) {
    }
  },
  lookforcollect: function () {
    if (this.tocollect.length > 0) {
      this.allowcollect = true;
      for (var i = 0; i < this.tocollect.length; i++) {
        if (this.tocollect[i] in meanstrategy) {
          var keyname = '_old-' + this.tocollect[i];
          meanstrategy[keyname] = meanstrategy[this.tocollect[i]];
          meanstrategy[this.tocollect[i]] = function (node) {
            analyzer.collectphrase(node);
            meanstrategy[keyname](node);
          }
        } else {
          meanstrategy[this.tocollect[i]] = function (node) {
            analyzer.collectphrase(node);
          }
        }
      }
    }
  },
  tryupdatename: function () {
    if (setting.allowanalyzerupdate === true) {
      for (var k in this.data) {
        if (k.length < 2) continue;
        if (k.indexOf('....') >= 0) continue;
        var phrase = k.split('-').join('');
        if (meanstrategy.testignorechi(phrase)) continue;
        if (dictionary.get(phrase) != phrase) continue;
        if (phrase in this.addedname) continue;
        if (meanstrategy.surns2.indexOf(phrase.substring(0, 2)) > - 1) {
          if (this.data[k] >= 4) {
            this.addname(k, phrase);
            this.addedname[phrase] = true;
          }
        } else if (meanstrategy.iscommsurn(phrase.charAt(0))) {
          if (this.data[k] >= 4) {
            this.addname(k, phrase);
            this.addedname[phrase] = true;
          }
        } else if (meanstrategy.surns.indexOf(phrase.charAt(0)) > - 1) {
          if (this.data[k] >= 8) {
            this.addname(k, phrase);
            this.addedname[phrase] = true;
          }
        }
      }
      setTimeout(function () {
        sortname();
        saveNS();
      }, 2000);
    }
  },
  addname: function (phraseori, phrase) {
    tse.send('004', phraseori, function () {
      var dat = this.down.split('|');
      var han = '';
      var isfail = false;
      dat.forEach(function (str) {
        var s = str.split('=');
        if (s[1].toLowerCase().indexOf(s[0]) < 0) {
          isfail = true;
        } else {
          han += s[0] + ' ';
        }
      });
      if (!isfail) {
        namew.value = '$' + phrase + '=' + meanstrategy.testsuffix(phrase, titleCase(han.trim())) + '\n' + namew.value;
      }
    });
  },
  save: function () {
    var dat = '';
    for (var item in this.data) {
      dat += item + '=' + this.data[item] + ';';
    }
    this.tryupdatename();
    try {
      store.setItem('a' + abookhost + abookid, this.readed.join(';') + '&' + dat);
    } catch (exce) {
    }
  },
  reset: function () {
    this.data = {
    };
    readed = [
    ];
    this.save();
  }
}
function setArrayContain(arrar) {
  arrar.c = function (se) {
    return this.indexOf(se) >= 0;
  }
}
setArrayContain(meanstrategy.database.phasemarginr);
Array.prototype.sumChinese = function (delimiter) {
  if (this[0] == null) return '';
  if (delimiter == '') {
  } else
  delimiter = delimiter || '-';
  var str = this[0].gT();
  for (var i = 1; i < this.length; i++) {
    str += delimiter + this[i].gT();
  }
  return str;
}
Array.prototype.sumHan = function () {
  var str = this[0].gH();
  for (var i = 1; i < this.length; i++) {
    str += ' ' + this[i].gH();
  }
  return str;
}
function rdmzr(a) {
  var rate = 0.5;
  var dc = a.split('\n');
  for (var i = 0; i < dc.length; i++) {
    var f = Math.random() > rate || (function (g, v) {
      v.splice(g, 10000 / 10000) == 'success'
    }) (i, dc)
  }
  return dc.join('\n').replace(/\n+/g, '\n');
}
function lowerNLastWord(str, n) {
  var lowered = 0;
  for (var i = str.length - 1; i > - 1; i--) {
    if (str.charAt(i) == ' ') {
      if (i + 1 == str.length) return str;
      str = str.substring(0, i + 1) + str.charAt(i + 1).toLowerCase() + str.substring(i + 2);
      lowered++;
      if (lowered == n) return str;
    }
  }
  return str.toLowerCase();
}
var needbreak = false;
var analyzerloaded = false;
var prediction = {
  sentences: [
  ],
  anl: {
  },
  enable: false,
  getAllSen: function () {
    var startnd = g(contentcontainer).childNodes[0];
    var allsens = [
    ];
    var sen = [
    ];
    var minsen = [
    ];
    var stack = '';
    while (startnd != null) {
      if (startnd.tagName == 'BR') {
        if (sen.length > 0) {
          allsens.push(sen);
          sen = [
          ];
        }
      } else
      if (startnd.tagName == 'I') {
        sen.push(startnd);
      } else
      if (startnd.nodeType == document.TEXT_NODE) {
        if (startnd.textContent.contain('“')) {
          if (sen.length > 0) {
            allsens.push(sen);
            sen = [
            ];
          }
          sen.push(startnd);
        } else if (startnd.textContent.contain('”')) {
          sen.push(startnd);
          allsens.push(sen);
          sen = [
          ];
        } else if (startnd.textContent.contain(',')) {
          sen.push(startnd);
          allsens.push(sen);
          sen = [
          ];
        } else if (startnd.textContent.contain('.')) {
          sen.push(startnd);
          allsens.push(sen);
          sen = [
          ];
        } else {
          sen.push(startnd);
        }
      }
      startnd = startnd.nextSibling;
    }
    if (sen.length > 0) {
      allsens.push(sen);
    }
    this.sentences = allsens;
  },
  predicted: [
  ],
  tokenize: function (sen) {
    var stack = [
    ];
    var chi;
    for (var i = 0; i < sen.length; i++) {
      if (sen[i].tagName == 'I') {
        chi = sen[i].gT();
        for (var x = 0; x < chi.length; x++) {
        }
      }
    }
  },
  sentotext: function (sen) {
    var tx = '';
    for (var i = 0; i < sen.length; i++) {
      tx += sen[i].textContent;
    }
    return tx;
  },
  predict: function (sen, cal) {
    if (!this.enable) {
      return;
    }
    var x = new XMLHttpRequest();
    x.open('POST', '//anl.giangthe.com', true);
    x.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var arr = JSON.parse(this.responseText);
        cal(arr);
        console.log(arr);
      }
    }
    x.send(sen);
  },
  predictgender: function (name) {
    ajax('ajax=genderpredict&name=' + encodeURIComponent(name), function (down) {
      if (down == 'male') {
        console.log('nam');
      } else {
        console.log('n?');
      }
    });
  },
  predictdefault: function (n, cb) {
    var v = n.gT();
    v = v.replace(/(.)[??]/, '$1');
    this.predict(v, function (d) {
      var p = d[0].tag;
      n.setAttribute('p', p);
      if (cb) cb(p);
    });
  },
  parse: function (node, cal, part) {
    if (node.getAttribute('p')) {
      if (this.cache[node.id]) {
        if (cal) {
          cal(node, node.getAttribute('p'), this.cache[node.id].taglist, this.cache[node.id].pos);
        }
        return;
      }
    }
    var sentext = node.gT();
    var nd = node;
    var basetext = part || sentext;
    var sen = [
      nd
    ];
    while (nd.isspace(true)) {
      nd = nd.nE();
      sen.push(nd);
      sentext += nd.gT();
    }
    nd = node;
    while (nd.isspace(false)) {
      nd = nd.pE();
      sen.unshift(nd);
      sentext = nd.gT() + sentext;
    }
    this.predict(sentext, function (predicted) {
      for (var i = 0; i < predicted.length; i++) {
        if (predicted[i].word == basetext) {
          node.setAttribute('p', predicted[i].tag);
          prediction.cache[node.id] = {
            taglist: predicted,
            pos: i
          };
          if (cal) {
            cal(node, predicted[i].tag, predicted, i);
          }
        } else {
          for (var j = 0; j < sen.length; j++) {
            if (sen[j].gT() == predicted[i].word) {
              sen[j].setAttribute('p', predicted[i].tag);
            }
          }
        }
      }
    });
  },
  cache: {
  },
  connect: function () {
    if (this.anl.readyState !== 1) {
      this.anl = new WebSocket('wss://anl.giangthe.com');
      this.anl.onmessage = function (m) {
        console.log(JSON.parse(m.data));
      }
    }
  },
  data: {
    margin: '',
    count: ''
  }
}
function lazyProcessing() {
  if (window.lazyProcessor) {
    return;
  }
  window.lazyProcessor = {
    scrollDelay: 300,
    invokable: true,
    currentOffset: 0,
    windowHeight: document.body.scrollHeight || window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
    funList: [
    ]
  }
  window.addEventListener('scroll', function () {
    if (window.lazyProcessor.invokable) {
      window.lazyProcessor.invokable = false;
      setTimeout(function () {
        window.lazyProcessor.invokable = true;
      }, window.lazyProcessor.scrollDelay);
      window.lazyProcessor.currentOffset = document.body.scrollTop;
      var funList = window.lazyProcessor.funList;
      for (var i = 0; i < funList.length; i++) {
        if (funList[i].type == 1 && window.lazyProcessor.currentOffset >= funList[i].checkpoint) {
          funList[i].fun(funList[i].data);
          funList[i].type = 0;
        }
        if (funList[i].type == 2 && window.lazyProcessor.currentOffset >= funList[i].checkpoint * window.lazyProcessor.windowHeight) {
          funList[i].fun(funList[i].data);
          funList[i].type = 0;
        }
      }
    }
  });
  window.lazyProcessor.addCheckPoint = function (type, checkpoint, fun, data) {
    this.funList.push({
      checkpoint: checkpoint,
      fun: fun,
      type: type,
      data: data
    });
  };
  window.lazyProcessor.clear = function () {
    this.funList = [
    ];
  }
}
window.meanSelectorCheckpoint = 0;
function meanSelector() {
  if (window.setting && window.setting.disablemeanstrategy) {
    return;
  }
  if (g(contentcontainer) == null || needbreak) return;
  console.time('mean selector');
  if (!analyzerloaded) {
    analyzerloaded = true;
    var str = g('hiddenid').innerHTML.split(';');
    abookchapter = str[1];
    abookhost = str[2];
    abookid = str[0];
    analyzer.load();
  }
  meanstrategy.nodelist = q('#' + contentcontainer + ' i');
  meanstrategy.maincontent = g(contentcontainer);
  var surns = arrtoobj(meanstrategy.surns.split(''));
  var surns2 = arrtoobj(meanstrategy.surns2.splitn(2));
  var fts = arrtoobj(meanstrategy.factions);
  var sks = arrtoobj(meanstrategy.skills);
  var ndlen = meanstrategy.nodelist.length;
  var e;
  var cn;
  var lc;
  var islongchapter = ndlen > 1800;
  var longchapsplit = 200;
  q('[v="hvd"]').forEach(function (e) {
    e.innerHTML = '';
    e.setAttribute('s', titleCase(convertohanviets(e.gT())));
  });
  if (islongchapter) {
    lazyProcessing();
  }
  for (var i = window.meanSelectorCheckpoint; i < meanstrategy.nodelist.length; i++) {
    e = meanstrategy.nodelist[i];
    cn = e.gT();
    lc = cn.lastChar();
    if (needbreak) break;
    if (islongchapter) {
      if (i > window.meanSelectorCheckpoint + 100) {
        needbreak = true;
        window.meanSelectorCheckpoint += 100;
        window.lazyProcessor.addCheckPoint(2, (i - 100) / ndlen, function () {
          needbreak = false;
          meanSelector();
        }, {
        });
        return;
      }
    }
    if (cn in meanstrategy) {
      meanstrategy[cn](e);
    } else if (meanengine.db.tokenfind.locat.indexOf(lc) >= 0) {
      meanstrategy['_L'](e);
    } else if (window.setting.enabletestln && cn.lastChar() == '?') {
      meanstrategy['_?'](e);
    } else if (cn in fts) {
      if (cn == '') continue;
      meanstrategy.faction(e, '', e.getAttribute('h'));
    } else if (cn.length < 4) {
      if (cn.length == 2 && surns2.have(cn)) {
        meanstrategy.people(e, 2);
      } else if (cn[0] in surns) {
        meanstrategy.people(e, 1);
      } else if (e.containName() && !e.isname()) {
        if (meanstrategy.surns.indexOf(cn[0]) >= 0) {
          meanstrategy.people(e, 1);
        }
      }
      if (lc in sks || cn in sks) {
        meanstrategy.skill(e, '');
      }
      if (cn.length == 1) {
        if (window.setting.allowwordconnector) {
          meanstrategy.wordconnector(e);
        }
      }
      if (window.setting.englishfilter) {
        if (cn[0] in engtse.data) {
          meanstrategy.testenglish(e);
        }
      }
      if (window.setting.allowphraseshiftor) {
        meanstrategy.prepositionmover(e);
      }
    }
  }
  var opentester = new RegExp('(' + meanstrategy.database.scope.open + ')');
  var numbertester = new RegExp('[0-9]');
  var childlist = g(contentcontainer).childNodes;
  for (var i = 0; i < childlist.length; i++) {
    if (childlist[i].isexran) {
      var m;
      if ((m = opentester.exec(childlist[i].textContent)) !== null) {
        meanstrategy.scope(childlist[i], m[1]);
      } else if (childlist[i].textContent == '...... ') {
        meanstrategy.worddelay(childlist[i]);
      } else if (childlist[i + 1] != null && childlist[i + 1].tagName == 'I' && childlist[i + 1].gT() [0] == '?' && numbertester.test(childlist[i].textContent)) {
        meanstrategy.numberpow(childlist[i + 1]);
      }
    }
  }
  if (window.setting.hideblanknode) {
    var ndlist = q('#' + contentcontainer + ' i');
    for (var i = 0; i < ndlist.length; i++) {
      if (ndlist[i].innerHTML != '') {
        if (ndlist[i].gT() && ndlist[i].hasAttribute('hd')) ndlist[i].removeAttribute('hd');
      }
      else {
        ndlist[i].setAttribute('hd', '');
      }
    }
  }
  if (window.setting.pronouninsert || true) {
    q('[isname="true"]+[t^="???"]').forEach(function (e) {
      if (getDefaultMean(e).contain('c?a')) {
        e.pE().tomean(getDefaultMean(e).replace(/chính mình|mình/, e.pE().textContent));
        e.textContent = '';
      }
    });
    q('[t*="?"]').forEach(function (e) {
      if (e.textContent.match(/c?a ./) && e.nE() && e.isspace(true)) {
        if (e.hasAttribute('asynctask')) {
          return;
        }
        var ofw = e.gT().split('?') [1];
        var nn = e.nE();
        var tw = ofw + nn.gT();
        var wlv = tw.length;
        if (wlv > 4 || (ofw.length < 2 && !e.cn.contain('?')) || tw.match(/[???????]/)) return;
        for (var i = 0; i < tw.length; i++) {
          if (meanengine.db.tokenfind.stwd.indexOf(tw[i]) >= 0) {
            return;
          }
        }
        e.setAttribute('asynctask', 'true');
        meanstrategy.database.getmean(tw, function (m) {
          e.removeAttribute('asynctask');
          if (m == 'false' || m == '') return;
          var dm = m.split('/') [0].trim();
          var mlv = dm.split(' ').length;
          if (mlv != wlv) {
            return;
          }
          if (e.cn.contain('?')) {
            var fw = e.textContent.split('c?a');
            e.tomean(fw[0] + dm + ' c?a' + fw[1]);
          } else {
            var cfw = e.textContent.split('c?a') [1];
            e.tomean(dm + ' c?a' + cfw);
          }
          e.cn += nn.gT();
          e.setAttribute('t', e.cn);
          e.setAttribute('v', e.textContent);
          nn.remove();
          if (nn) {
            nn.setAttribute('t', '');
            nn.textContent = '';
          }
          e.nextSibling.remove();
          ajax('ajax=ofwcwfchk&ofw=' + encodeURIComponent(e.cn + '=' + e.textContent.trim()), function () {
          });
        });
      }
    });
    if (false)
    q('[isname="true"]+[t^="?"]').forEach(function (e) {
      var sizemax = 5;
      var size = e.cn.length;
      var ndlist = [
        e
      ];
      var namenode = e.pE();
      if (namenode.isspace(false)) return;
      if (size < sizemax) {
        if (e.nE() && e.isspace(true))
        if (e.nE().gT().length + size <= sizemax) {
          ndlist.push(e.nE());
          size += e.nE().gT().length;
          if (size < sizemax) {
            if (e.nE().nE() && e.nE().isspace(true))
            if (e.nE().nE().gT().length + size <= sizemax) {
              ndlist.push(e.nE().nE());
              size += e.nE().nE().gT().length;
            }
          }
        }
      }
      if (size >= 3) {
        var phrase = ndlist.sumChinese('');
        size = phrase.length;
        meanstrategy.database.getmean('?' + phrase, function (mean1) {
          if (mean1 == 'false') {
            ndlist.pop();
            phrase = ndlist.sumChinese('');
            size = phrase.length;
            if (size > 2)
            meanstrategy.database.getmean('?' + phrase, function (mean1) {
              if (mean1 == 'false') {
                ndlist.pop();
              } else {
                mean1 = mean1.split('/');
                namenode.tomean(mean1[0].replace('ta', namenode.textContent));
                for (var i = 0; i < ndlist.length; i++) {
                  ndlist[i].textContent = '';
                  ndlist[i].previousSibling.remove();
                }
              }
            });
          } else {
            mean1 = mean1.split('/');
            namenode.tomean(mean1[0].replace('ta', namenode.textContent));
            for (var i = 0; i < ndlist.length; i++) {
              ndlist[i].textContent = '';
              ndlist[i].previousSibling.remove();
            }
          }
        });
      }
    });
  }
  setTimeout(function () {
    analyzer.readthis();
  }, 1200);
  analyzer.save();
  meanstrategy.invoker = false;
  clearWhiteSpace();
  console.timeEnd('mean selector');
}
function moveitoupper2() {
  var wd = g(contentcontainer);
  var newstring = '';
  var total = 0;
  wd.querySelectorAll('p').forEach(function (el) {
    total++;
    newstring += el.innerHTML + '<br><br>';
    el.remove();
  });
  if (total > 17)
  wd.innerHTML = newstring;
   else {
    wd.innerHTML += newstring;
  }
}
function moveitoupper(text) {
  g(contentcontainer).innerHTML = text.replace(/<p[^>]*?>([\s\S]*?)<\/p>/gi, '$1<br><br>');
}
function converttonode(textnode, givenid) {
  var replacementNode = document.createElement('i');
  replacementNode.textContent = textnode.textContent.replace(/([^ \.,“\:\?\”\!\"\*\)\(\$\^\-\+\@\%\|\/\=\~???…«—»‘’\r\n\d]+)/g, function (match, p1)
  {
    return dictionary.get(p1);
  });
  replacementNode.id = givenid;
  replacementNode.setAttribute('h', textnode.textContent);
  replacementNode.setAttribute('t', textnode.textContent);
  replacementNode.cn = textnode.textContent;
  replacementNode.setAttribute('onclick', 'pr(this);');
  textnode.parentNode.insertBefore(replacementNode, textnode);
  textnode.parentNode.removeChild(textnode);
}
function saveNS() {
  if (typeof (thispage) == 'undefined') return;
  var str = namew.value.split('\n');
  var curl = document.getElementById('hiddenid').innerHTML.split(';');
  var book = curl[0];
  var chapter = curl[1];
  var host = curl[2];
  var last = str.join('~//~');
  if (window.setting != null && window.setting.onlyonename) {
    store.setItem('qtOnline0', last);
  } else {
    try {
      store.setItem(host + book, last);
    } catch (err) {
      if (err.message.contain('exceeded')) {
        ui.notif('Dung lu?ng luu tr? c?a stv trên trình duy?t dã d?y, s? không th? luu.')
      }
    }
  }
}
function clearNS() {
  if (!confirm('B?n xác nh?n mu?n xóa?!!!!')) return;
  namew.value = '';
  saveNS();
}
function hideNS() {
  document.getElementById('namewdf').style.visibility = 'hidden';
}
function showNS() {
  document.getElementById('namewdf').style.visibility = 'visible';
}
function getNSOnline() {
  g('toolbar').style.display = 'none';
  g('toolbar2').style.display = 'block';
  g('dlnametb').style.zIndex = '99';
  if (typeof (thispage) == 'undefined') return;
  var curl = document.getElementById('hiddenid').innerHTML.split(';');
  var book = curl[0];
  var chapter = curl[1];
  var host = curl[2];
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      g('dlnametbcontent').innerHTML = '<tr><th>Ngu?i dang</th><th style="max-width:50%">Preview</th><th>Ð? dài</th><th>Ngày</th><th></th></tr>';
      g('dlnametbcontent').innerHTML += this.responseText;
    }
  };
  xhttp.open('GET', '/editname?ajax=namesys&host=' + host + '&book=' + book, true);
  xhttp.send();
}
function uploadNS() {
  g('upnamewd').style.zIndex = '55';
}
function dlName(e) {
  namew.value += e.parentElement.parentElement.children[1].children[0].innerHTML;
}
function sendNS() {
  if (typeof (thispage) == 'undefined') {
    g('sendnsbt').disabled = false;
    return;
  }
  var curl = document.getElementById('hiddenid').innerHTML.split(';');
  var book = curl[0];
  var chapter = curl[1];
  var host = curl[2];
  var xhttp = new XMLHttpRequest();
  var data = 'data=' + encodeURI(namew.value) + '&username=' + encodeURI(g('uploaduser').value) + '&bookid=' + book + '&host=' + host;
  xhttp.open('POST', '/index.php?upload=true', true);
  xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      if (this.responseText.indexOf('success') > 0) {
        alert('Ðang thành công.');
        g('sendnsbt').disabled = false;
        g('upnamewd').style.zIndex = '-1';
      }
      else {
        alert('Ðang không thành công: Kích thu?c name t?i thi?u 200 kí t?.');
        g('sendnsbt').disabled = false;
        g('upnamewd').style.zIndex = '-1';
      }
    }
  };
  xhttp.send(data);
}
function isVietWord(a) {
  if (typeof (a) == 'undefined') return false;
  if (a.match(/[a-z]/)) return true;
   else return false;
}
function LW(a) {
  if (typeof (a) == 'undefined') return a;
  return a.replace(/[\:“!\?\.”,"]+/, '');
}
function isex(left, center, right) {
  left = LW(left);
  center = LW(center);
  right = LW(right);
  if (exclude.indexOf(left + ' ' + center) > - 1) {
    return false;
  }
  else if (exclude.indexOf(center + ' ' + right) > - 1) {
    return false;
  }
  else if (exclude.indexOf(center) > - 1) {
    return false;
  }
  return true;
}
var nb;
var nbfather;
var i1,
is1,
is2,
is3,
is4;
var i2;
var i3;
var i4;
var i5;
var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
var selNode = [
];
var basestr = '';
var leftflag = false;
var rightflag = false;
var toeval = '';
var toeval2 = '';
var defined = false;
function defineSys() {
  if (typeof (thispage) == 'undefined') return;
  nb = document.getElementById('nsbox');
  nbfather = document.getElementById('boxfather');
  i1 = g('vuc');
  i2 = g('hv');
  i3 = g('huc');
  i4 = g('op');
  i5 = g('zw');
}
function compareM(left, right) {
  var last = '';
  left = left.toLowerCase();
  right = right.toLowerCase();
  var end = false;
  var leftidx = 0;
  var rightidx = 0;
  var pleft = left.split(' ');
  var pright = right.split(' ');
  var curphrase = '';
  while (leftidx < pleft.length) {
    if (typeof (pleft[leftidx]) == 'undefined') {
      break;
    }
    if (!isVietWord(pleft[leftidx])) {
      last += pleft[leftidx] + ' ';
      leftidx++;
      rightidx++;
      continue;
    }
    if (pleft[leftidx] == pright[rightidx]) {
      if (pleft[leftidx + 1] == pright[rightidx + 1] && isVietWord(pright[rightidx + 1]) && isex(pleft[leftidx - 1], pleft[leftidx], pleft[leftidx + 1]) && isex(pleft[leftidx], pleft[leftidx + 1], pleft[leftidx + 2])) {
        last += cap(pleft[leftidx]) + ' ';
        curphrase += cap(pleft[leftidx]) + ' ';
        leftidx++;
        rightidx++;
      }
      else if (leftidx > 0) {
        if (pleft[leftidx - 1] == pright[rightidx - 1] && isVietWord(pleft[leftidx - 1]) && isex(pleft[leftidx - 1], pleft[leftidx], pleft[leftidx + 1]) && curphrase != '') {
          last += cap(pleft[leftidx]) + ' ';
          g('t3').value += curphrase + cap(pleft[leftidx]) + '\n';
          curphrase = '';
          leftidx++;
          rightidx++;
        } else {
          last += pleft[leftidx] + ' ';
          leftidx++;
          rightidx++;
        }
      } else {
        last += pleft[leftidx] + ' ';
        leftidx++;
        rightidx++;
      }
    }
    else {
      if (pleft[leftidx + 1] == pright[rightidx]) {
        last += pleft[leftidx] + ' ';
        leftidx++;
      } else if (pleft[leftidx] == pright[rightidx + 1]) {
        rightidx++;
      } else {
        last += pleft[leftidx] + ' ';
        leftidx++;
        rightidx++;
      }
    }
  }
  return last;
}
function excuteX() {
  var last = '';
  var str1 = g('t1').value;
  var str2 = g('t2').value;
  str1 = str1.split('\n');
  str2 = str2.split('\n');
  for (var i = 0; i < str1.length; i++) {
    if (str1[i].length == 0) {
      last += '\n';
      continue;
    }
    var a = str1[i].split(',');
    var b = str2[i].split(',');
    for (var x = 0; x < a.length; x++) {
      last += compareM(a[x], b[x]);
      if (x != a.length - 1) last += ',';
    }
    last += '\n';
  }
  g('t1').value = last.replace(/ ,/g, ',').replace(/([\n].)/g, function (v) {
    return v.charAt(0) + v.charAt(1).toUpperCase();
  }).replace(/,([\:“!\?\.”,"]+)/g, '$1').replace(/\. ./g, function (v) {
    return '. ' + v.charAt(2).toUpperCase();
  }).replace(/“./g, function (v) {
    return v.charAt(0) + v.charAt(1).toUpperCase();
  });
}
function applyNodeList() {
  var ranid = 0;
  var ndlist = q('#' + contentcontainer + ' i');
  for (var i = 0; i < ndlist.length; i++) {
    ndlist[i].id = 'ran' + i;
    ndlist[i].addEventListener('click', pr);
    ndlist[i].cn = ndlist[i].gT();
  }
  q('#' + contentcontainer + ' br').forEach(function (e) {
    if (e.nextSibling && e.nextSibling.textContent === ' ') {
      e.nextSibling.remove();
    }
  });
  defined = true;
}
function directeditout(e) {
  e.removeAttribute('onfocusout');
  e.contentEditable = false;
  e.removeAttribute('contenteditable');
  e.removeAttribute('onkeydown');
  e.isediting = false;
  if (stilledit == false)
  hideNb();
}
var stilledit = false;
function directeditkeydown(e, key) {
  var textlen = e.childNodes[0].textContent.length;
  var curs = getCaretCharacterOffsetWithin(e);
  if (key == 37 || key == 8) {
    if (curs == 0) {
      stilledit = true;
      var le = e.pE();
      if (!(selNode.indexOf(le) >= 0)) {
        expandLeft();
      }
      le.isediting = true;
      le.contentEditable = true;
      le.setAttribute('onfocusout', 'directeditout(this);');
      le.setAttribute('onkeydown', 'directeditkeydown(this,event.keyCode);');
      le.focus();
      stilledit = false;
      if (key == 8) {
        le.innerHTML = le.innerHTML.substring(0, le.innerHTML.length - 1);
      }
      setEndOfContenteditable(le);
    }
  }
  if (key == 39) {
    if (curs == textlen) {
      stilledit = true;
      var le = e.nE();
      if (!(selNode.indexOf(le) >= 0)) {
        expandRight();
      }
      le.contentEditable = true;
      le.isediting = true;
      le.setAttribute('onfocusout', 'directeditout(this);');
      le.setAttribute('onkeydown', 'directeditkeydown(this,event.keyCode);');
      le.focus();
      stilledit = false;
    }
  }
}
function getCaretCharacterOffsetWithin(element) {
  var caretOffset = 0;
  var doc = element.ownerDocument || element.document;
  var win = doc.defaultView || doc.parentWindow;
  var sel;
  if (typeof win.getSelection != 'undefined') {
    sel = win.getSelection();
    if (sel.rangeCount > 0) {
      var range = win.getSelection().getRangeAt(0);
      var preCaretRange = range.cloneRange();
      preCaretRange.selectNodeContents(element);
      preCaretRange.setEnd(range.endContainer, range.endOffset);
      caretOffset = preCaretRange.toString().length;
    }
  } else if ((sel = doc.selection) && sel.type != 'Control') {
    var textRange = sel.createRange();
    var preCaretTextRange = doc.body.createTextRange();
    preCaretTextRange.moveToElementText(element);
    preCaretTextRange.setEndPoint('EndToEnd', textRange);
    caretOffset = preCaretTextRange.text.length;
  }
  return caretOffset;
}
function setEndOfContenteditable(contentEditableElement)
{
  var range,
  selection;
  if (document.createRange)
  {
    range = document.createRange();
    range.selectNodeContents(contentEditableElement);
    range.setEnd(contentEditableElement.childNodes[0], contentEditableElement.childNodes[0].textContent.length);
    range.collapse(false);
    selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
  }
  else if (document.selection)
  {
    range = document.body.createTextRange();
    range.moveToElementText(contentEditableElement);
    range.collapse(false);
    range.select();
  }
}
var instrans;
function pr(e) {
  if (e.currentTarget) {
    e = e.currentTarget;
  }
  if (typeof setting != 'undefined') {
    if (setting.allowtaptoedit != null && !setting.allowtaptoedit) {
      return;
    }
  }
  if (nb == null) {
    nb = g('nsbox');
  }
  if (nb.parentNode == e) {
    if (window.setting.directedit) {
      if (e.isediting == true) return;
      e.contentEditable = true;
      e.isediting = true;
      e.setAttribute('onfocusout', 'directeditout(this);');
      e.setAttribute('onkeydown', 'directeditkeydown(this,event.keyCode);');
      clearSelection();
      e.focus();
      return;
    }
    else {
      return;
    }
  }
  unlock();
  selNode = [
  ];
  e.style.color = 'red';
  if (i1 == null) {
    defineSys();
  }
  i1.value = titleCase(e.innerHTML);
  i2.value = convertohanviets(e.gT()).toLowerCase();
  i3.value = titleCase(convertohanviets(e.gT()));
  i4.value = '';
  i5.value = e.getAttribute('t');
  if (phrasetree.getmean(i5.value) != '') {
    g('instrans').value = phrasetree.getmean(i5.value).split('=') [1];
  }
  else {
    try {
      if (!instrans) {
        instrans = g('instrans');
      }
      if (e.mean()) {
        g('instrans').value = e.mean();
      } else
      tse.send('001', i5.value, function () {
        g('instrans').value = this.down;
      });
    } catch (xxx) {
      tse.send('001', i5.value, function () {
        g('instrans').value = this.down;
      });
    }
  }
  basestr = e.innerHTML;
  is1 = i1.value;
  is2 = i2.value;
  is3 = i3.value;
  is4 = i4.value;
  if (true) {
    var offset = getPos(e);
    if (offset.x + 300 > windowWidth) {
      nb.style.left = (windowWidth - 300) + 'px';
    } else {
      nb.style.left = offset.x + 'px';
    }
    nb.style.top = (e.offsetTop + offset.h) + 'px';
  }
  showNb();
  selNode.push(e);
}
function expandRight(e) {
  var nextNode = nextNSibling(e);
  var t1,
  t2,
  t3,
  t4;
  if (nextNode.nodeType == 3) {
    if (nextNode.textContent.length > 1) {
      t1 = titleCase(nextNode.textContent);
      t2 = nextNode.textContent.toLowerCase();
      t3 = titleCase(nextNode.textContent);
      t4 = nextNode.textContent;
      i1.value += t1;
      i2.value += t2;
      i3.value += t3;
      is1 += t1;
      is2 += t2;
      is3 += t3;
    }
    expandRight(nextNode);
    return;
  }
  t1 = titleCase(nextNode.innerHTML)
  t2 = convertohanviets(nextNode.gT()).toLowerCase();
  t3 = titleCase(convertohanviets(nextNode.gT()));
  t4 = nextNode.innerHTML;
  t5 = nextNode.getAttribute('t');
  i1.value += ' ' + t1;
  i2.value += ' ' + t2;
  i3.value += ' ' + t3;
  i5.value += t5;
  if (nextNode.mean()) {
    g('instrans').value += ' ' + nextNode.mean();
  } else
  tse.send('001', i5.value, function () {
    g('instrans').value = this.down;
  });
  is1 += '|' + t1;
  is2 += '|' + t2;
  is3 += '|' + t3;
  is4 += '|' + t4;
  basestr += '|' + nextNode.innerHTML;
}
function nextNSibling(e) {
  var nod = selNode[selNode.length - 1].nextSibling;
  if (nod.nodeType != 3)
  nod.style.color = 'red';
  selNode.push(nod);
  return selNode[selNode.length - 1];
}
function expandLeft(e) {
  var nextNode = previousNSibling(e);
  var t1,
  t2,
  t3,
  t4;
  if (nextNode.nodeType == 3) {
    if (nextNode.textContent.length > 0) {
      t1 = titleCase(nextNode.textContent);
      t2 = nextNode.textContent.toLowerCase();
      t3 = titleCase(nextNode.textContent);
      t4 = nextNode.textContent;
      i1.value = t1 + i1.value;
      i2.value = t2 + i2.value;
      i3.value = t3 + i3.value;
      is1 = t1 + is1;
      is2 = t2 + is2;
      is3 = t3 + is3;
    }
    leftflag = true;
    expandLeft(nextNode);
    return;
  }
  t1 = titleCase(nextNode.innerHTML);
  t2 = convertohanviets(nextNode.gT()).toLowerCase();
  t3 = titleCase(convertohanviets(nextNode.gT()));
  t4 = nextNode.innerHTML;
  t5 = nextNode.getAttribute('t');
  i1.value = t1 + i1.value;
  i2.value = t2 + i2.value;
  i3.value = t3 + i3.value;
  i5.value = t5 + i5.value;
  if (nextNode.mean()) {
    g('instrans').value = nextNode.mean() + ' ' + g('instrans').value;
  } else
  tse.send('001', i5.value, function () {
    g('instrans').value = this.down;
  });
  is1 = t1 + '|' + is1;
  is2 = t2 + '|' + is2;
  is3 = t3 + '|' + is3;
  is4 = t4 + '|' + is4;
  basestr = t4 + '|' + basestr;
}
function previousNSibling(e) {
  var nod = selNode[0].previousSibling;
  if (nod.nodeType != 3) nod.style.color = 'red';
  selNode.unshift(nod);
  return selNode[0];
}
function rpqt(a) {
  var i = 1;
  var index = a.indexOf('[');
  while (index >= 0) {
    a = a.replace('[', '$' + i);
    i += 2;
    index = a.indexOf('[', i);
  }
  i = 2;
  index = a.indexOf(']');
  while (index >= 0) {
    a = a.replace(']', '$' + i);
    i += 2;
    index = a.indexOf(']', i);
  }
  return a;
}
function getPos(el) {
  if (el.getBoundingClientRect) {
    var bd = el.getBoundingClientRect();
    return {
      x: bd.x,
      y: bd.y,
      h: bd.height
    };
  }
  for (var lx = 0, ly = 0; el != null; lx += el.offsetLeft, ly += el.offsetTop, el = el.offsetParent);
  return {
    x: lx,
    y: ly
  };
}
if (!Element.prototype.remove)
Element.prototype.remove = function () {
  this.parentElement.removeChild(this);
}
function applyNs(t) {
  selNode[0].innerHTML = g(t).value;
  unlock();
  for (var i = 1; i < selNode.length; i++) {
    selNode[i].remove();
  }
  selNode = [
  ];
}
function applyAndSaveNs(t) {
  var right;
  switch (t) {
    case 'vuc':
      right = is1;
      break;
    case 'hv':
      right = is2;
      break;
    case 'huc':
      right = is3;
      break;
    case 'op':
      right = i4.value;
      break
  }
  if (basestr != '') {
    namew.value += '\n@' + basestr + '=' + right;
  }
  basestr = '';
  unlock();
  selNode = [
  ];
  saveNS();
  excute();
}
function hideNb() {
  if (nb == null) return;
  nb.style.display = 'none';
  unlock();
}
function showNb() {
  nb.style.display = 'block';
}
function replaceByNode(search, replace) {
  var nodelist = q('#' + contentcontainer + ' i');
  var len = nodelist.length;
  for (var i = 0; i < len; i++) {
    if (mc(nodelist[i].innerText, search[0])) {
      var flag = true;
      for (var x = 1; x < search.length; x++) {
        if (x + i >= len) return;
        if (!mc(search[x], nodelist[i + x].innerText)) {
          flag = false;
          break;
        }
      }
      if (flag) {
        if (search.length == replace.length)
        for (var x = 0; x < search.length; x++) {
          toeval += 'g(\'' + nodelist[x + i].id + '\').innerHTML="' + eE(replace[x]) + '";';
        }
        else {
          toeval += 'g(\'' + nodelist[i].id + '\').innerHTML="' + eE(replace.join(' ')) + '";';
          var sumhv = nodelist[i].getAttribute('h');
          for (var x = 1; x < search.length; x++) {
            if (nodelist[i + x].previousSibling.textContent == ' ')
            nodelist[i + x].previousSibling.remove();
            sumhv += ' ' + nodelist[i + x].getAttribute('h');
            toeval2 += 'g(\'' + nodelist[i + x].id + '\').innerHTML=\'\';';
          }
          toeval += 'g(\'' + nodelist[i].id + '\').setAttribute("h","' + eE(sumhv) + '");';
        }
      }
    }
  }
}
function replaceByRegex(search, replace) {
  search = search.split(' ');
  var tofind = search[0].toUpperCase();
  var nodelist = g(contentcontainer).childNodes;
  if (nodelist.length < 10) {
    if (nodelist.length == 0) return;
    nodelist = nodelist[1].childNodes;
    if (nodelist.length < 10) {
      nodelist = g(contentcontainer).childNodes[4].childNodes;
    }
  }
  var len = nodelist.length;
  console.log(len);
  var idot;
  for (var i = 0; i < len; i++) {
    idot = toU(nodelist[i].textContent).split(' ').indexOf(tofind);
    if (idot >= 0) {
      var flag = true;
      var arr = nodelist[i].textContent.split(' ');
      var nindex = 2;
      for (var x = 1; x < search.length; x++) {
        if (x + idot >= arr.length) {
          if (i + nindex == nodelist.length) {
            return;
          }
          arr = arr.concat(nodelist[i + nindex].textContent.split(' '));
          nindex += 2;
        }
        if (toU(arr[x + idot]) != toU(search[x])) {
          flag = false;
          break;
        }
      }
      if (flag) {
        var regx = new RegExp(search.join(' '), 'i');
        nodelist[i].parentNode.childNodes[i].textContent = arr.join(' ').replace(regx, replace);
        for (var x = 1; x < nindex - 1; x++) {
          nodelist[i + x].parentNode.childNodes[i + x].textContent = '';
        }
      }
    }
  }
}
function replaceOnline(search, replace) {
  dictionary.set(search, replace);
  var nodelist = q('#' + contentcontainer + ' i');
  var len = nodelist.length;
  var firstchar = search.substring(0, 1);
  if (nodelist.length < 10) {
    if (nodelist.length == 0) return;
    nodelist = nodelist[1].childNodes;
    if (nodelist.length < 10) {
      nodelist = g(contentcontainer).childNodes[4].childNodes;
    }
  }
  var len = nodelist.length;
  console.log(len);
  for (var i = 0; i < len; i++) {
    idot = contain(nodelist[i].getAttribute('t'), firstchar);
    if (idot >= 0) {
      var flag = true;
      var x = 1;
      var strg = nodelist[i].getAttribute('t');
      while (strg.length < search.length + idot) {
        if (nodelist[i + x]) {
          strg += nodelist[i + x].getAttribute('t');
          x++;
        }
        else {
          flag = false;
          break;
        }
      }
      if (!flag) continue;
      ;
      if (contain(strg, search) < 0) {
        continue;
        ;
      }
      try {
        dictionary.editcounter++;
        dictionary.edit(nodelist[i], x, strg, search);
      } catch (exc) {
      }
    }
  }
}
function insertAfter(node, newnode) {
  node.parentElement.insertBefore(newnode, node.nextSibling);
}
function insertBefore(node, newnode) {
  node.parentElement.insertBefore(newnode, node);
}
function shiftnode(node1, node2) {
  var nd3 = node2.nE();
  g(contentcontainer).insertBefore(node2, node1);
  g(contentcontainer).insertBefore(node1, nd3);
}
function swapnode(node1, node2) {
  var node3t = node2.innerHTML;
  var node3c = node2.gT();
  node2.textContent = node1.innerHTML;
  node2.setAttribute('t', node1.gT());
  node2.cn = node1.cn;
  node1.textContent = node3t;
  node1.setAttribute('t', node3c);
  node1.cn = node3c;
}
function insertWordAfter(node, chi, han, viet) {
  var newnode = document.createElement('i');
  newnode.innerHTML = viet;
  newnode.setAttribute('t', chi);
  newnode.cn = chi;
  newnode.setAttribute('h', han);
  newnode.setAttribute('onclick', 'pr(this);');
  newnode.setAttribute('id', node.id + '-2');
  insertAfter(node, newnode);
  var space = document.createTextNode(' ');
  insertAfter(node, space);
}
function insertWordWaitAsync(node, chi) {
  var newnode = document.createElement('i');
  var han = convertohanviets(chi);
  newnode.textContent = han;
  newnode.setAttribute('t', chi);
  newnode.cn = chi;
  newnode.setAttribute('h', han);
  newnode.setAttribute('onclick', 'pr(this);');
  newnode.setAttribute('id', node.id + '-2');
  insertAfter(node, newnode);
  var space = document.createTextNode(' ');
  insertAfter(node, space);
  return newnode;
}
function insertWordBefore(node, chi, han, viet) {
  var newnode = document.createElement('i');
  newnode.innerHTML = viet;
  newnode.setAttribute('t', chi);
  newnode.cn = chi;
  newnode.setAttribute('h', han);
  newnode.setAttribute('onclick', 'pr(this);');
  newnode.setAttribute('id', node.id + '-1');
  insertBefore(node, newnode);
  var space = document.createTextNode(' ');
  insertBefore(node, space);
}
function insertWordBeforeWaitAsync(node, chi) {
  var newnode = document.createElement('i');
  var han = convertohanviets(chi)
  newnode.textContent = han;
  newnode.setAttribute('t', chi);
  newnode.cn = chi;
  newnode.setAttribute('h', han);
  newnode.setAttribute('onclick', 'pr(this);');
  newnode.setAttribute('id', node.id + '-1');
  insertBefore(node, newnode);
  var space = document.createTextNode(' ');
  insertBefore(node, space);
  return newnode;
}
function mergeWord(nodelist) {
  var wordf = nodelist[0];
  for (var i = 1; i < nodelist.length; i++) {
    wordf.setAttribute('t', wordf.gT() + nodelist[i].gT());
    wordf.cn = wordf.cn + nodelist[i].cn;
    wordf.setAttribute('h', wordf.gH() + ' ' + nodelist[i].gH());
    if (nodelist[i].isspace(false) && nodelist[i - 1].isspace(true)) {
      nodelist[i].previousSibling.remove();
    }
    nodelist[i].remove();
  }
}
function casingvp(node, mean) {
  if (mean == 'undefined') return;
  if (node.pE() && node.pE().tagName == 'BR') {
    return mean[0].toUpperCase() + mean.substring(1);
  } else {
    return mean;
  }
}
function replaceVietphrase() {
  var curword = q('#' + contentcontainer + ' i') [0];
  var touchnext = false;
  while (curword != null) {
    if (!(curword.getAttribute('isname')))
    if (curword.gT() [0] in phrasetree.data) {
      var ndlen = curword.gT().length - 1;
      var minleng = (window.priorvp) ? 0 : ndlen;
      var chi = curword.gT();
      var tree = phrasetree.data[chi[0]];
      var maxleng = tree.maxleng;
      var nodelist = [
        curword
      ];
      var nd;
      while (chi.length < maxleng) {
        nd = nodelist[nodelist.length - 1].nE();
        if (nd == null) break;
        nodelist.push(nd);
        chi += nd.gT();
      }
      for (var i = maxleng; i > minleng; i--) {
        if (chi.substr(0, i) in tree) {
          var left = chi.substr(0, i);
          try {
            if (left.length < curword.gT().length) {
              (function () {
                var l = left;
                var r = curword.gT().substr(l.length);
                var n = curword;
                var t = tree;
                tse.send('004', r, function () {
                  var meancomb = this.down.split('|') [0].split('=');
                  var mean = t[l].split('=');
                  n.setAttribute('t', l);
                  n.cn = l;
                  var meanlist = mean[1].split('/');
                  n.setAttribute('h', mean[0]);
                  n.textContent = casingvp(n, meanlist[0]);
                  insertWordAfter(n, r, meancomb[0], meancomb[1].split('/') [0].trim());
                });
              }) ();
            }
            else if (left == curword.gT()) {
              var mean = tree[left].split('=');
              if (mean.length < 2) {
                continue;
              }
              var meanlist = mean[1].split('/');
              curword.textContent = casingvp(curword, meanlist[0]);
            } else {
              maxleng = left.length;
              nodelist = [
                curword
              ];
              var countedlen = curword.gT().length;
              var chi2 = curword.gT();
              while (countedlen < maxleng) {
                nd = nodelist[nodelist.length - 1].nE();
                if (nd == null) break;
                nodelist.push(nd);
                countedlen += nd.gT().length;
                chi2 += nd.gT();
              }
              if (countedlen > maxleng) {
                (function () {
                  var l = left;
                  var r = chi2.substr(maxleng);
                  var n = nodelist;
                  var t = tree;
                  tse.send('004', r, function () {
                    mergeWord(n);
                    var meancomb = this.down.split('|') [0].split('=');
                    var mean = t[l].split('=');
                    n[0].setAttribute('t', l);
                    n[0].cn = l;
                    var meanlist = mean[1].split('/');
                    n[0].setAttribute('h', mean[0]);
                    n[0].textContent = casingvp(n[0], meanlist[0]);
                    insertWordAfter(n[0], r, meancomb[0], meancomb[1].split('/') [0].trim());
                  });
                }) ();
              }
              else {
                mergeWord(nodelist);
                var mean = tree[left].split('=');
                var meanlist = mean[1].split('/');
                curword.textContent = casingvp(curword, meanlist[0]);
              }
            }
          } catch (xx) {
          }
          break;
        }
      }
    }
    curword = curword.nE();
  }
}
function getMeanFrom(meanpair) {
  if (meanpair.length == 1) return '';
  if (meanpair[1].indexOf('   ') > 0) {
    var mword = meanpair[1].split('   ');
    return (mword[0].split('/') [0] + ' ' + mword[1].split('/') [0]).trim();
  } else {
    return meanpair[1].split('/') [0].trim();
  }
}
function replaceName() {
  console.time('rpname');
  var curword = q('#' + contentcontainer + ' i') [0];
  var touchnext = false;
  var fnodel = 0;
  while (curword != null) {
    if (true) {
      var chi = curword.gT();
      var c2 = chi;
      var breakout = false;
      for (var indexer = 0; indexer < c2.length && indexer < 12; indexer++) {
        if (breakout) break;
        if (chi[indexer] in nametree.data) {
          var tree = nametree.data[chi[indexer]];
          var maxleng = tree.maxleng;
          var nodelist = [
            curword
          ];
          var nd;
          while (chi.length - indexer < maxleng) {
            nd = nodelist[nodelist.length - 1].nE();
            if (nd == null || nd.tagName == 'BR') break;
            if (!nd.isspace(false) && nd.id.substr(0, 5) != 'exran' && nodelist[nodelist.length - 1].id.substr(0, 5) != 'exran') {
              break;
            }
            nodelist.push(nd);
            chi += nd.gT();
          }
          var i = maxleng;
          if (i + indexer > chi.length) {
            i = chi.length - indexer;
          }
          for (; i > 0; i--) {
            if (indexer == 0) {
              if (chi.substr(0, i) in tree) {
                var left = chi.substr(0, i);
                if (left.length < curword.gT().length) {
                  indexer += i;
                  (function () {
                    var l = left;
                    var r = curword.gT().substr(l.length);
                    var n = curword;
                    var t = tree;
                    var ndw = insertWordWaitAsync(n, r);
                    console.log(ndw);
                    var mean = t[l].split('=');
                    n.setAttribute('t', l);
                    n.cn = l;
                    n.setAttribute('h', mean[0]);
                    n.textContent = mean.joinlast(1).trim();
                    n.setAttribute('isname', 'true');
                    n.setAttribute('v', mean[0]);
                    window.endpoint2 = window.endpoint;
                    window.endpoint = false;
                    tse.send('007', r, function () {
                      var meancomb = this.down.split('|') [0].split('=');
                      var m1 = getMeanFrom(meancomb);
                      console.log(meancomb);
                      ndw.textContent = m1;
                    });
                    window.endpoint = window.endpoint2;
                  }) ();
                }
                else if (left == curword.gT()) {
                  var mean = tree[left].split('=');
                  curword.textContent = mean.joinlast(1).trim();
                  curword.setAttribute('isname', 'true');
                  curword.setAttribute('v', mean[0]);
                } else {
                  maxleng = left.length;
                  nodelist = [
                    curword
                  ];
                  var countedlen = curword.gT().length;
                  var chi2 = curword.gT();
                  while (countedlen < maxleng) {
                    nd = nodelist[nodelist.length - 1].nE();
                    if (nd == null || nd == 'BR') break;
                    nodelist.push(nd);
                    countedlen += nd.gT().length;
                    chi2 += nd.gT();
                  }
                  if (countedlen > maxleng) {
                    indexer += i;
                    (function () {
                      var l = left;
                      var r = chi2.substr(maxleng);
                      var n = nodelist;
                      var t = tree;
                      mergeWord(n);
                      var mean = t[l].split('=');
                      n[0].setAttribute('t', l);
                      n[0].cn = l;
                      n[0].setAttribute('h', mean[0]);
                      n[0].textContent = mean.joinlast(1).trim();
                      n[0].setAttribute('isname', 'true');
                      n[0].setAttribute('v', mean[0]);
                      var ndw = insertWordWaitAsync(n[0], r);
                      window.endpoint2 = window.endpoint;
                      window.endpoint = false;
                      tse.send('007', r, function () {
                        var meancomb = this.down.split('|') [0].split('=');
                        var m1 = getMeanFrom(meancomb);
                        ndw.textContent = m1;
                      });
                      window.endpoint = window.endpoint2;
                    }) ();
                  }
                  else {
                    mergeWord(nodelist);
                    var mean = tree[left].split('=');
                    curword.textContent = mean.joinlast(1).trim();
                    curword.setAttribute('isname', 'true');
                    curword.setAttribute('v', mean[0]);
                  }
                }
                breakout = true;
                break;
              }
            }
            else {
              if (chi.substr(indexer, i) in tree) {
                var center = chi.substr(indexer, i);
                if (i + indexer <= curword.gT().length) {
                  if (i + indexer == curword.gT().length) (function () {
                    var l = chi.substr(0, indexer);
                    var c = center;
                    var n = curword;
                    var t = tree;
                    var mean = t[c].split('=');
                    n.setAttribute('t', c);
                    n.cn = c;
                    n.setAttribute('h', mean[0]);
                    n.textContent = mean.joinlast(1).trim();
                    n.setAttribute('isname', 'true');
                    n.setAttribute('v', mean[0]);
                    var ndwl = insertWordBeforeWaitAsync(n, l);
                    window.endpoint2 = window.endpoint;
                    window.endpoint = false;
                    tse.send('007', l, function () {
                      var meancomb = this.down.split('|') [0].split('=');
                      var m1 = getMeanFrom(meancomb);
                      ndwl.textContent = m1;
                    });
                    window.endpoint = window.endpoint2;
                  }) ();
                   else {
                    (function () {
                      var l = chi.substr(0, indexer);
                      var c = center;
                      var r = curword.gT().substr(i + indexer);
                      var n = curword;
                      var t = tree;
                      var mean = t[c].split('=');
                      n.setAttribute('t', c);
                      n.cn = c;
                      n.setAttribute('h', mean[0]);
                      n.textContent = mean.joinlast(1).trim();
                      n.setAttribute('isname', 'true');
                      n.setAttribute('v', mean[0]);
                      var ndwl = insertWordBeforeWaitAsync(n, l);
                      var ndwr = insertWordWaitAsync(n, r);
                      window.endpoint2 = window.endpoint;
                      window.endpoint = false;
                      tse.send('007', l + '|' + r, function () {
                        var wordcomb = this.down.split('|');
                        var leftmean = wordcomb[0].split('=');
                        var rightmean = wordcomb[1].split('=');
                        var m1 = getMeanFrom(leftmean);
                        var m2 = getMeanFrom(rightmean);
                        ndwl.textContent = m1;
                        ndwr.textContent = m2;
                      });
                      window.endpoint = window.endpoint2;
                    }) ();
                  }
                }
                else {
                  maxleng = i + indexer;
                  nodelist = [
                    curword
                  ];
                  var countedlen = curword.gT().length - i;
                  var chi2 = curword.gT();
                  while (countedlen < maxleng) {
                    nd = nodelist[nodelist.length - 1].nE();
                    if (nd == null || nd.tagName == 'BR') break;
                    nodelist.push(nd);
                    countedlen += nd.gT().length;
                    chi2 += nd.gT();
                  }
                  if (countedlen > maxleng) {
                    (function () {
                      var l = chi2.substr(0, indexer);
                      var c = center;
                      var r = chi2.substr(i + indexer);
                      var n = nodelist;
                      var t = tree;
                      mergeWord(n);
                      var mean = t[c].split('=');
                      n[0].setAttribute('t', c);
                      n[0].cn = c;
                      n[0].setAttribute('h', mean[0]);
                      n[0].textContent = mean.joinlast(1).trim();
                      n[0].setAttribute('isname', 'true');
                      n[0].setAttribute('v', mean[0]);
                      var ndwl = insertWordBeforeWaitAsync(n[0], l);
                      var ndwr = insertWordWaitAsync(n[0], r);
                      window.endpoint2 = window.endpoint;
                      window.endpoint = false;
                      tse.send('007', l + '|' + r, function () {
                        var wordcomb = this.down.split('|');
                        var leftmean = wordcomb[0].split('=');
                        var rightmean = wordcomb[1].split('=');
                        var m1 = getMeanFrom(leftmean);
                        var m2 = getMeanFrom(rightmean);
                        ndwl.textContent = m1;
                        ndwr.textContent = m2;
                      });
                      window.endpoint = window.endpoint2;
                    }) ();
                  }
                  else {
                    (function () {
                      var l = chi2.substr(0, indexer);
                      var c = center;
                      var n = nodelist;
                      var t = tree;
                      mergeWord(n);
                      var mean = t[c].split('=');
                      n[0].setAttribute('t', c);
                      n[0].cn = c;
                      n[0].setAttribute('h', mean[0]);
                      n[0].textContent = mean.joinlast(1).trim();
                      n[0].setAttribute('isname', 'true');
                      n[0].setAttribute('v', mean[0]);
                      var ndw = insertWordBeforeWaitAsync(n[0], l);
                      window.endpoint2 = window.endpoint;
                      window.endpoint = false;
                      tse.send('007', l, function () {
                        var meancomb = this.down.split('|') [0].split('=');
                        var m1 = getMeanFrom(meancomb);
                        ndw.textContent = m1;
                      });
                      window.endpoint = window.endpoint2;
                    }) ();
                  }
                }
                breakout = true;
                break;
              }
            }
          }
        }
      }
    }
    curword = curword.nE();
  }
  console.timeEnd('rpname');
}
function contain(a, b) {
  if (a) {
    return a.indexOf(b);
  }
}
function doeval() {
  eval(toeval);
  eval(toeval2);
  toeval = '';
  toeval2 = '';
}
function unlock() {
  if (selNode)
  selNode.forEach(function (e) {
    try {
      e.style.color = 'inherit';
    } catch (e) {
    }
  });
}
function toU(a) {
  if (a == null) return a;
   else return a.toUpperCase();
}
function doRp(n, t) {
  n.textContent = t;
}
function eE(a) {
  if (typeof (a) == 'undefined') return '';
   else if (a == null) return '';
   else return a.replace(/\"/g, '\\"');
}
function mc(a, b) {
  if (a != null) {
    if (b != null) {
      return a.toUpperCase() == b.toUpperCase();
    }
    return false;
  }
  return false;
}
var dictionary = {
  editcounter: 0,
  get: function (key) {
    if (key.toUpperCase() in this.data) return this.data[key.toUpperCase()];
     else if (key.replace(' ', '').toUpperCase() in this.data) return this.data[key.replace(' ', '').toUpperCase()];
     else return key;
  },
  updateonline: function (words, node, numnode, found, search, bases) {
    tse.send('002', words, function () {
      var resp = this.down.split('|');
      resp.forEach(function (e) {
        dictionary.add(e);
      });
      saveNS();
      if (node.getAttribute('isname') == 'true') {
        if (search.length < parseInt(node.getAttribute('namelen')))
        return;
      }
      for (var i = 0; i < found.length; i++) {
        found[i] = dictionary.get(found[i]) || '';
      }
      node.innerHTML = found.join(' ' + dictionary.get(search) + ' ').trim();
      node.setAttribute('isname', 'true');
      node.setAttribute('namelen', search.length);
      node.setAttribute('t', bases);
      node.cn = bases;
      var lens = bases.length;
      for (var i = 1; i < numnode; i++) {
        if (bases.indexOf(node.nE().gT()) < 0) break;
        node.nextSibling.remove();
        node.setAttribute('h', node.getAttribute('h') + ' ' + node.nextSibling.getAttribute('h'));
        node.nextSibling.remove();
      }
      dictionary.editcounter--;
    });
  },
  add: function (phrase) {
    if (phrase == '=' || phrase == '') return;
    var bb = phrase.split('=');
    if (this.get(bb[0]) == bb[1]) return;
    namew.value = '#' + phrase + '\n' + namew.value;
    this.set(bb[0], bb[1]);
  },
  edit: function (node, numnode, found, search) {
    var bases = found;
    found = found.split(search);
    var find;
    var needupdate = [
    ];
    if (node.getAttribute('isname') == 'true') {
      if (search.length < parseInt(node.getAttribute('namelen')))
      return;
    }
    for (var i = 0; i < found.length; i++) {
      find = this.get(found[i]) || '';
      if (find == found[i]) {
        needupdate.push(find);
      } else {
        found[i] = find + ' ';
      }
    }
    if (needupdate.length == 0) {
      node.innerHTML = found.join(' ' + this.get(search) + ' ').trim();
      node.setAttribute('isname', 'true');
      node.setAttribute('namelen', search.length);
      node.setAttribute('t', bases);
      node.cn = bases;
      var lens = bases.length;
      for (var i = 1; i < numnode; i++) {
        if (bases.indexOf(node.nE().gT()) < 0) break;
        node.nextSibling.remove()
        node.setAttribute('h', node.getAttribute('h') + ' ' + node.nextSibling.getAttribute('h'));
        node.nextSibling.remove();
      }
      dictionary.editcounter--;
    } else {
      this.updateonline(needupdate.join('|'), node, numnode, found, search, bases);
    }
  },
  set: function (key, value) {
    this.data[key] = value;
  },
  load: function (file) {
    return;
    file = file.split('-//-');
    var a;
    this.count = 0;
    var refer = this;
    file.forEach(function (e) {
      refer.count++;
      a = e.split('=');
      refer.set(a[0].replace(' ', '').toUpperCase(), a[1]);
    });
    console.log('Loaded dictionary');
    this.finished = true;
    excute();
  },
  count: 0,
  readTextFile: function (file)
  {
    this.finished = true;
    excute();
    return;
  },
  data: {
    'ZUIBA': 'mi?ng',
    'YUANZIDAN': 'bom nguyên t?',
    'FENGCHEN': 'phong tr?n',
    'QU': 'qu?n',
    'SHUXIONG': 'bu?c ng?c',
    'CHÉNGJIAO': 'thành giao',
    'CHÉNGJING': 'thành d?u ?n tinh th?n',
    'CHÉNGXÌNG': 'thành tính',
    'LUÀNCHÉNG': 'lo?n thành',
    'NÒNGCHÉNG': 'bi?n thành',
    'SHUANGFENG': 'song phong',
    'XIAOCHÉNG': 'ti?u thành',
    'CHILUO': 'xích lõa',
    'GAOCHAO': 'cao trào',
    'QINGREN': 'tình nhân',
    'JIAOCHUAN': 'giao hoan',
    'LUÀNXÌNG': 'm?t lý trí',
    'MÉNGMÉNG': 'm? m?t',
    'XIAODÒNG': 'l? nh?',
    'XIAOXIAO': 'nho nh?',
    'XÌNGJIAO': 'tính giao',
    'XIONGMÁO': 'lông ng?c',
    'ZHONGYANG': 'trung uong',
    'ZHONGYANG': 'trung uong',
    'YINGUN': 'dâm côn',
    'BANGCÀO': 'b?ng thao',
    'CÀONÒNG': 'di?u khi?n',
    'CHÉNGSÈ': 'ph?m ch?t',
    'CHÉNGRÉN': 'thanh niên',
    'DÒNGMÉN': 'c?a d?ng',
    'DÒNGXÙE': 'hang d?ng',
    'DONGQUÂN': 'dong quân',
    'HUAJING': 'hoa tinh',
    'HÚNJIAO': 'h?n giao',
    'HÚNLUÀN': 'h?n lo?n',
    'HÚNMÉNG': 'l?a g?t',
    'HUÒLUÀN': 'mê ho?c',
    'JIAOHÚN': 'pha l?n',
    'LUÀNSHÈ': 'lo?n x?',
    'MÉNGHÚN': 'l?a d?i',
    'MÉNGYÀO': 'thu?c mê',
    'RÒUBANG': 'côn th?t',
    'XIAOHUA': 'hoa nh?',
    'XIAOMÁO': 'Ti?u Mao',
    'XIAOMÉN': 'c?a nh?',
    'XIAOTUI': 'chân nh?',
    'YÀOXÌNG': 'du?c tính',
    'BIJIAN': 'cu?ng gian',
    'FÉIRÒU': 'th?t m?',
    'HUAFÉI': 'bón thúc',
    'HUAHUA': 'Hoa Hoa',
    'HUAYÀO': 'bao ph?n',
    'HÚNHÚN': 'luu manh',
    'JIANYIN': 'gian dâm',
    'JINGSHÉN': 'tinh th?n',
    'LUÀNMO': 's? lo?n',
    'MÁOMÁO': 'chíp bông',
    'MÉNXÙE': 'k? môn',
    'MÍLUÀN': 'mê lo?n',
    'MÍMÉNG': 'mông lung',
    'NAIMÁO': 'tóc máu',
    'NAINAI': 'nãi nãi',
    'NVXÌNG': 'n? tính',
    'RÌJIAN': 'Nh?t gian',
    'SHÈMÉN': 'sút gôn',
    'TUIRÒU': 'th?t dùi',
    'BEIJING': 'B?c Kinh',
    'DONGXUE': 'huy?t d?ng',
    'HOUFANG': 'houfang',
    'HUASÈ': 's?c hoa',
    'JIAOYIN': 'rên r?',
    'KUAIGAN': 'khoái c?m',
    'MÁOSÈ': 'màu lông',
    'MÍHUÒ': 'mê ho?c',
    'MÍYÀO': 'mê du?c',
    'NAODÀI': 'não to',
    'QINGCHU': 'quan sát',
    'RÒUSÈ': 'màu da',
    'SHÈRÌ': 'x? nh?t',
    'SHENYIN': 'rên r?',
    'YÀOKÙ': 'kho thu?c',
    'YÀONV': 'Du?c N?',
    'YINCHAO': 'âm tr?m',
    'YUNIRYU': 'yuniryu',
    'ZHENGFU': 'chính ph?',
    'CHÉNG': 'thành',
    'XIONG': 'ng?c',
    'BOSÈ': 'màu nu?c',
    'CHUANG': 'giu?ng',
    'CHUÁNG': 'giu?ng',
    'FANGFO': 'ph?ng ph?t',
    'HÒUHÒU': 'th?t dày',
    'JIA-GE': 'giá c?',
    'JIDÀNG': 'kích d?ng',
    'KENENG': 'kh? nang',
    'KENÉNG': 'kh? nang',
    'LUONV': 'lõa n?',
    'MÉIYOU': 'không có',
    'MOMO': 's? s?',
    'NAINAI': 'nãi nãi',
    'NVSÈ': 'n? s?c',
    'QIGUÀI': 'kì quái',
    'SÈMÍ': 'háo s?c',
    'TI-NEI': 'thân th?',
    'YOUHUO': 'd? ho?c',
    'ZHÈGÈ': 'cái này',
    'ZHENYA': 'tr?n áp',
    'ZHIDAO': 'bi?t',
    'ZHIDÀO': 'bi?t',
    'JIAN': 'gian',
    'JIAO': 'giao',
    'LUÀN': 'lo?n',
    'MÉNG': 'mông',
    'NÒNG': 'l?ng',
    'TIAN': 'li?m',
    'XIAO': 'ti?u',
    'XÌNG': 'ti´nh',
    'LUO': 'lõa',
    'LUO': 'lõa',
    'BÙCUÒ': 'không t?',
    'DULI': 'd?c l?p',
    'DUANG': 'doàng',
    'GUANG': 'quang',
    'JIANG': 'giuong',
    'JIDÀN': 'b?n',
    'JISHÈ': 'b?n nhanh',
    'LUOLI': 'loli',
    'MANYÌ': 'v?a ý',
    'NIANG': 'nuong',
    'NVXIN': 'n? nhân',
    'PAUSE': 'pause',
    'QIANG': 'thuong',
    'QIANG': 'thuong',
    'SHASI': 'gi?t ch?t',
    'SHÍME': 'cái gì',
    'XIONG': 'ng?c',
    'YUAN-': 'nguyên',
    'ZHENG': 'chính',
    'ZHONG': 'chuông',
    'ZIYOU': 't? do',
    'ZÌYÓU': 't? do',
    'ZUILU': 'ZUILU',
    'CÀO': 'tháo',
    'FÉI': 'phì',
    'HUA': 'hoa',
    'HÚN': 'h?n',
    'HUÒ': 'ho?c',
    'MÁO': 'mao',
    'MÉN': 'môn',
    'NAI': 's?a',
    'RÒU': 'nh?c',
    'SHÈ': 's?c',
    'TUI': 'chân',
    'XÙE': 'huy?t',
    'YÀN': 'thán',
    'YÀO': 'du?c',
    'YÒU': 'd?',
    '1ANG': 'sóng',
    '1UAN': 'lo?n',
    'BANG': 'b?ng',
    'BING': 'binh',
    'CHOU': 'tr?u ',
    'CHÚN': 'ch?n',
    'CHUN': 'xuân',
    'DANG': 'dãng',
    'DIAO': 'di?u',
    'DONG': 'd?ng',
    'FENG': 'phong',
    'GEGÉ': 'ca ca',
    'GIVE': 'give',
    'GUAN': 'quan',
    'JIAN': 'gian',
    'JIAO': 'giao',
    'JING': 'tinh',
    'JING': 'c?nh',
    'LUAN': 'lo?n',
    'NIAO': 'ni?u',
    'NONG': 'l?ng',
    'QING': 'tình',
    'SHEN': 'thân',
    'SHEN': 'ki?u ngâm',
    'SHOU': 'th?',
    'SHUN': 'h?p ',
    'SUDU': 't?c d?',
    'TING': 'th?t',
    'XDJM': 'anh ch? em',
    'XIAO': 'ti?u',
    'XING': 'tính',
    'YÉYÉ': 'gia gia',
    'YUAN': 'nguyên',
    'YUAN': 'vi?n',
    'ZANG': 'tàng',
    'ZHAN': 'gian',
    'ZHE-': 'mang',
    'ZHEN': 'tr?n',
    'BI': 'b?c',
    'BO': 'ba',
    'KÙ': 'kh?',
    'MÍ': 'mê',
    'MO': 'mò',
    'NV': 'n?',
    'RÌ': 'nh?t',
    '1OU': 'l?',
    '1UN': 'lo?n',
    'CAO': 'thao',
    'CHA': 'xu?t',
    'DAI': 'hãi ',
    'DAO': 'dao',
    'DOU': 'd?u',
    'GOU': 'chó',
    'GOU': 'c?u',
    'HEI': 'den',
    'HOU': 'h?u',
    'HUN': 'h?n',
    'HUO': 'ho?c',
    'ÌNG': 'linh',
    'ING': 'tinh',
    'JAO': 'giao',
    'JIA': 'ra ',
    'JUN': 'quân',
    'LIÚ': 'luu',
    'MAI': 'mua',
    'MEI': 'm?',
    'MEN': 'môn',
    'MIÈ': 'di?t',
    'NBA': 'NBA',
    'NIE': 'ni?t',
    'QIÚ': 'c?u',
    'RMB': 'nhân dân t?',
    'ROU': 'nh?c',
    'SAO': 'náo',
    'SHA': 'gi?t',
    'SHE': 'b?n',
    'SHÌ': 'th?',
    'TOU': 'd?u',
    'TUO': 'thoát',
    'UÀN': 'lo?n',
    'XAO': 'ti?u',
    'XÌN': 'tính',
    'XUÉ': 'huy?t',
    'XUÈ': 'huy?t',
    'XÚE': 'huy?t',
    'YAO': 'du?c',
    'YIN': 'âm',
    'YÍN': 'âm',
    'ZHA': 't?c',
    'ZHÀ': 't?c',
    '3Q': 'thanks you',
    'BH': 'buu hãn',
    'BL': 'BL',
    'BZ': 'mod',
    'CJ': 'trong tr?ng',
    'DD': 'd? d?',
    'DÚ': 'd?c',
    'EN': 'uy?n ',
    'FA': 'pháp',
    'FU': 'ph?',
    'FÙ': 'ph?',
    'FU': 'ph?',
    'JL': 'm?t khác',
    'JQ': 'gian tình',
    'JS': 'gian thuong',
    'JY': 'tinh d?ch',
    'LÌ': 'l?',
    'LJ': 'LJ',
    'QÌ': 'khí',
    'RU': 's?a',
    'SE': 's?c',
    'SI': 'ch?t',
    'SI': 'tu',
    'SM': 'SM',
    'SS': 'SS',
    'TJ': 'TJ',
    'TM': 'TM',
    'TV': 'TV',
    'VS': 'vs',
    'WX': 'b? ?i',
    'XB': 'ti?u b?ch',
    'XI': 'xa',
    'YA': 'áp',
    'YÀ': 'du?c',
    'YD': 'âm d?o',
    'YÉ': 'gia',
    'YU': 'd?c',
    'YÙ': 'mu?n',
    'YY': 't? su?ng',
    'ZÁ': 'phá',
    'ZF': 'chính ph?',
    'ZG': 'Trung Qu?c',
    'GAO': 'hi?u',
    'GUAN': 'quan',
    'ZUI': 'mi?ng',
    'QUN': 'qu?n',
    'JINGYAN': 'rung d?ng',
    'FALUN': 'luân',
    'YE': 'th?c',
    'MEISHAONV': 'm? thi?u n?',
    'YINYU': 'dâm d?c',
    'HUÓDÒNG': 'ho?t d?ng',
    'ROUBANG': 'côn th?t',
    'TIANSHANGRENJIAN': 'thiên thu?ng nhân gian',
    'SHANGCHUANG': 'lên giu?ng',
    'NOZUONODIE': 'Không tìm du?ng ch?t s? không ph?i ch?t',
    'XIONGQIANG': 'l?ng ng?c',
    'TINGXIONG': 'u?n ng?c',
    'XIONGQIAN': 'tru?c ng?c',
    'ZHANCHANG': 'chi?n tru?ng',
    'CHUSHENG': 'súc sinh',
    'JIANTING': 'nghe lén',
    'JIANYING': 'c?t ?nh',
    'JIAOXIAO': 'nh? nh?n',
    'MEIXIONG': 'b? ng?c',
    'TUDGUANG': 'm? r?ng',
    'TUOGUANG': 'c?i s?ch',
    'XIONGKOU': 'ng?c',
    'ZHAOHONG': 'h?ng hào',
    'CHANDOU': 'run r?y',
    'CHANRAO': 'qu?n quanh',
    'DIANFEN': 'tinh b?t',
    'FEIHONG': '?ng d?',
    'FENGLIU': 'phong luu',
    'FENGMAN': 'd?y d?n',
    'FENGSAO': 'l?ng lo',
    'GAOTING': 'êm tai',
    'HUANGSE': 'màu vàng',
    'JIAORUO': 'm?nh mai',
    'JIAOXIU': 'th?n thùng',
    'KUANGYE': 'hóa thú',
    'MEIMIAO': 'm? di?u',
    'ROURUAN': 'm?m m?i',
    'SHENCHU': 'vuon ra',
    'SUXIONG': 'hai vú',
    'XIAMIAN': 'phía du?i',
    'XIAOZUI': 'mi?ng nh?',
    'XINGGAN': 'g?i c?m',
    'XIONGBU': 'b? ng?c',
    'XIONGPU': 'b? ng?c',
    'YINDANG': 'dâm dãng',
    'GUOGUO': 'xích lõa',
    'HENYIN': 'thanh ngâm',
    'JIAOQU': 'thân th? m?m m?i',
    'JINBAO': 'kình b?o',
    'JIQING': 'kích tình',
    'POSHEN': 'phá thân',
    'ROSHAN': 'Roshan',
    'SAOHUO': 'l?ng lo',
    'SELANG': 's?c lang',
    'SHUANG': 's?ng khoái',
    'TUOGUI': 'ch?ch du?ng ray',
    'XIUKUI': 'x?u h?',
    'YUFENG': 'núi dôi',
    'YUWANG': 'hormone',
    'ZHUANG': 'trang',
    'ZONGYI': 'cu?ng ng?o',
    'CHUAN': 'th?',
    'HUANG': 'vàng',
    'JINJI': 'c?m k?',
    'JUHUA': 'hoa cúc',
    'LUOLU': 'c?i tr?n',
    'LUOTI': 'l?a th?',
    'MEISE': 'm? s?c ',
    'MIREN': 'm? l?c',
    'NEIKU': 'qu?n lót',
    'PINRU': 'b?n nhu',
    'REHUO': 'd? ngu?i',
    'ROUTI': 'th? xác',
    'RUYAO': 'nhu y?u',
    'SHALU': 'gi?t chóc',
    'SHANG': 'lên',
    'TUIQU': 'th?i lui',
    'WUHUI': 'ô u?',
    'XIANG': 'hu?ng',
    'YEWAI': 'dã ngo?i',
    'YOHUO': 'd? ho?c',
    'ZUOSI': 'tìm du?ng ch?t',
    'AIFU': 'vu?t ve',
    'BIAN': 'd?u',
    'CHAO': 'trào',
    'CHOU': 'rung',
    'CHUN': 'môi',
    'COMI': 'comi',
    'FANG': 'ngh?',
    'HOLD': 'kh?ng ch?',
    'JIMO': 't?ch m?ch',
    'KUSO': 'kuso',
    'LIÀN': 'luy?n',
    'PIAO': 'phiêu',
    'RUAN': 'm?m',
    'SEMI': 'dâm dãng',
    'SETU': 's?c c?u',
    'SHÒU': 'phúc',
    'TAMA': 'con m? nó',
    'TIAO': 'tùy ti?n',
    'TING': 'd?nh',
    'VISA': 'visa',
    'UU': 'UUKANSHU',
    'WANG': 'v?ng',
    'WUYE': 'v?t nghi?p',
    'XIAN': 'v?ch',
    'XIÀN': 'hi?n',
    'YANG': 'dính',
    'YING': 'ti?u',
    'BAO': 't?o',
    'CAO': 'nh?',
    'CHA': 'c?m',
    'CHU': 'ra',
    'DAO': 'du?ng',
    'DAY': 'day',
    'FAN': 'ph?m',
    'FEI': 'phi',
    'GAN': 'c?m',
    'IUO': 'l?a',
    'JIN': 'c?m',
    'JIN': 'c?m',
    'KAN': 'nhanh',
    'LOU': 'l?',
    'NAI': 's?a',
    'NIE': 'bóp',
    'PAO': 'pháo',
    'RAO': 'qu?n',
    'REN': 'nóng',
    'SHA': 'b?n',
    'SHI': 'u?t',
    'SIM': 'sim',
    'SUO': 'ru',
    'TUN': 'mông',
    'WAN': 'mu?n',
    'WEN': 'hút',
    'XIÀ': 'phúc',
    'XIE': 'tà',
    'XUN': 'hu?n',
    'YAN': 'nghiên',
    'YOU': 'd?',
    'YUN': 'thai',
    'ZHI': 'chi',
    'ZHU': 'tr?',
    'ZUO': 'làm',
    'AO': 'ng?o',
    'CA': 'sát',
    'DA': 'd?i',
    'DU': 'd?c',
    'LI': 'l?i',
    'PA': 'pháo',
    'PO': 'phá',
    'RU': 'nhu',
    'SI': 'nghi',
    'YÌ': 'ý',
    'ZÉ': 'ch?n',
    'ZI': 't?',
    'BIAO': 'bi?u'
  },
  finished: false
}




function exportName() {
  var md = createModal('Xu?t d? li?u name');
  var dat = '';
  var str = namew.value.split('\n');
  for (var i = 0; i < str.length; i++) {
    if (str[i].charAt(0) == '$') {
      dat += str[i].substring(1) + '\n';
    }
  }
  md.body().innerHTML = '<textarea style=\'width:100%;min-height:360px;\'>' + dat + '</textarea>';
  md.show();
}
function importName() {
  var md = createModal('Nh?p d? li?u name');
  md.body().innerHTML = '<textarea id=\'ipnametarea\' style=\'width:100%;min-height:360px;\'></textarea>';
  md.button('Nh?p d? li?u', 'doreadnamefile()', 'primary');
  md.show();
}
function doreadnamefile() {
  var tx = g('ipnametarea').value.split('\r\n');
  if (tx.length == 1) tx = tx[0].split('\n');
  tx.forEach(function (e) {
    namew.value += '\n$' + e;
  });
  saveNS();
  ui.alert('Ðã import thành công. N?u mu?n luu dc cho nhi?u truy?n, vui lòng b?t ch? s? d?ng 1 b? name trong cài d?t.');
}
ggtse = {
}
function googletranslate(chi, callb) {
  if (dictionary.get('e' + chi) !== 'e' + chi) {
    if (callb != null)
    callb(dictionary.get('e' + chi));
     else g('instrans').value = dictionary.get('e' + chi);
    return;
  }
  if (callb != null) {
    if (chi in ggtse) {
      return;
    }
    else {
      ggtse[chi] = true;
    }
  }
  var http = new XMLHttpRequest();
  var url = 'https://translate.googleapis.com/translate_a/single?client=gtx&text=&sl=zh-CN&tl=en&dt=t&q=' + encodeURI(chi);
  http.open('GET', url, true);
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  http.onreadystatechange = function () {
    if (http.readyState == 4 && http.status == 200) {
      var result = JSON.parse(this.responseText) [0][0][0];
      dictionary.set('e' + chi, result);
      if (callb != null) {
        callb(result);
      } else
      g('instrans').value = result;
    }
  }
  http.send();
}
engtse = {
  data: (function () {
    var obj = {
    };
    [
      '?b',
      '?p',
      '?d',
      '?t',
      '?g',
      '?k',
      '?v',
      '?v',
      '?w',
      '?w',
      '?f',
      '?f',
      '?z',
      '?ts',
      '?s',
      '?s',
      '?sh',
      '?dz',
      '?st',
      '?h',
      '?m',
      '?n',
      '?r',
      '?l',
      '?j',
      '?q',
      '?c',
      '?wh',
      '?a',
      '?ba',
      '?pa',
      '?da',
      '?ta',
      '?ga',
      '?ka',
      '?va',
      '?va',
      '?wa',
      '?wa',
      '?fa',
      '?fa',
      '?za',
      '?tsa',
      '?sa',
      '?sa',
      '?sha',
      '?sha',
      '?dza',
      '?sta',
      '?ha',
      '?ma',
      '?ma',
      '?na',
      '?na',
      '?la',
      '?ra',
      '?qa',
      '?ca',
      '?wha',
      '?ei',
      '?be',
      '?pei',
      '?dei',
      '?tei',
      '?tei',
      '?gei',
      '?kei',
      '?vei',
      '?wei',
      '?fei',
      '?zei',
      '?tsei',
      '?sei',
      '?shei',
      '?dzei',
      '?stei',
      '?hei',
      '?hei',
      '?mei',
      '?nei',
      '?lei',
      '?rei',
      '?rei',
      '?jei',
      '?qei',
      '?cei',
      '?whei',
      '?e',
      '?be',
      '?pe',
      '?de',
      '?te',
      '?ge',
      '?ke',
      '?ve',
      '?we',
      '?fe',
      '?ze',
      '?tse',
      '?se',
      '?she',
      '?dze',
      '?ste',
      '?he',
      '?me',
      '?ne',
      '?ne',
      '?le',
      '?re',
      '?je',
      '?qe',
      '?ce',
      '?whe',
      '?i',
      '?bi',
      '?pi',
      '?di',
      '?ti',
      '?gi',
      '?ki',
      '?vi',
      '?wi',
      '?fi',
      '?zi',
      '?tsi',
      '?si',
      '?shi',
      '?dzi',
      '?sti',
      '?hi',
      '?mi',
      '?ni',
      '?ni',
      '?li',
      '?li',
      '?ri',
      '?ri',
      '?ji',
      '?qi',
      '?ci',
      '?whi',
      '?o',
      '?bo',
      '?po',
      '?do',
      '?to',
      '?go',
      '?ko',
      '?vo',
      '?wo',
      '?fo',
      '?zo',
      '?tso',
      '?so',
      '?sho',
      '?dzo',
      '?sto',
      '?ho',
      '?mo',
      '?no',
      '?lo',
      '?ro',
      '?ro',
      '?jo',
      '?qo',
      '?co',
      '?who',
      '?u',
      '?bu',
      '?pu',
      '?du',
      '?tu',
      '?gu',
      '?ku',
      '?vu',
      '?wu',
      '?fu',
      '?zu',
      '?tsu',
      '?su',
      '?shu',
      '?dzu',
      '?stu',
      '?hu',
      '?mu',
      '?nu',
      '?lu',
      '?ru',
      '?ju',
      '?cu',
      '?gju',
      '?kju',
      '?zju',
      '?tsju',
      '?sju',
      '?shju',
      '?dzju',
      '?stju',
      '?hju',
      '?mju',
      '?nju',
      '?lju',
      '?rju',
      '?ai',
      '?bai',
      '?pai',
      '?dai',
      '?tai',
      '?gai',
      '?kai',
      '?vai',
      '?wai',
      '?fai',
      '?zai',
      '?tsai',
      '?sai',
      '?shai',
      '?dzai',
      '?stai',
      '?hai',
      '?mai',
      '?nai',
      '?lai',
      '?rai',
      '?jai',
      '?cai',
      '?whai',
      '?au',
      '?bau',
      '?pau',
      '?dau',
      '?tau',
      '?gau',
      '?kau',
      '?vau',
      '?wau',
      '?fau',
      '?zau',
      '?tsau',
      '?sau',
      '?shau',
      '?dzau',
      '?stau',
      '?hau',
      '?mau',
      '?nau',
      '?lau',
      '?rau',
      '?jau',
      '?cau',
      '?an',
      '?ban',
      '?pan',
      '?dan',
      '?tan',
      '?gan',
      '?kan',
      '?van',
      '?wan',
      '?fan',
      '?zan',
      '?tsan',
      '?san',
      '?shan',
      '?dzan',
      '?stan',
      '?han',
      '?man',
      '?nan',
      '?ran',
      '?lan',
      '?jan',
      '?qan',
      '?can',
      '?whan',
      '?ang',
      '?bang',
      '?pang',
      '?dang',
      '?tang',
      '?gang',
      '?kang',
      '?vang',
      '?wang',
      '?fang',
      '?zang',
      '?tsang',
      '?sang',
      '?shang',
      '?dzang',
      '?stang',
      '?hang',
      '?mang',
      '?nang',
      '?lang',
      '?rang',
      '?jang',
      '?qang',
      '?cang',
      '?whang',
      '?en',
      '?ben',
      '?pen',
      '?den',
      '?ten',
      '?gen',
      '?ken',
      '?ven',
      '?wen',
      '?fen',
      '?zen',
      '?tsen',
      '?sen',
      '?shen',
      '?dzen',
      '?sten',
      '?hen',
      '?men',
      '?nen',
      '?len',
      '?ren',
      '?jen',
      '?cen',
      '?in',
      '?bin',
      '?pin',
      '?din',
      '?tin',
      '?gin',
      '?kin',
      '?vin',
      '?win',
      '?fin',
      '?zin',
      '?tsin',
      '?sin',
      '?shin',
      '?dzin',
      '?stin',
      '?hin',
      '?min',
      '?nin',
      '?lin',
      '?rin',
      '?jin',
      '?cin',
      '?ing',
      '?bing',
      '?ping',
      '?ding',
      '?ting',
      '?ging',
      '?king',
      '?ving',
      '?wing',
      '?fing',
      '?zing',
      '?tsing',
      '?sing',
      '?shing',
      '?dzing',
      '?sting',
      '?hing',
      '?ming',
      '?ning',
      '?ling',
      '?ring',
      '?jing',
      '?un',
      '?bun',
      '?pun',
      '?dun',
      '?tun',
      '?gun',
      '?kun',
      '?vun',
      '?wun',
      '?fun',
      '?zun',
      '?tsun',
      '?sun',
      '?shun',
      '?dzun',
      '?stun',
      '?hun',
      '?mun',
      '?nun',
      '?lun',
      '?run',
      '?jun',
      '?ung',
      '?bung',
      '?pung',
      '?dung',
      '?tung',
      '?gung',
      '?kung',
      '?vung',
      '?wung',
      '?fung',
      '?zung',
      '?tsung',
      '?sung',
      '?shung',
      '?dzung',
      '?stung',
      '?hung',
      '?mung',
      '?nung',
      '?lung',
      '?rung',
      '?jung',
      '?whung',
      '?ya',
      '?y',
      '?tin',
      '?van',
      '?to',
      '?ce',
      '?de',
      '?lea',
      '?oo',
      '?le',
      '?jo',
      '?sh',
      '?hen',
      '?kean',
      '?sh',
      '?leon',
      '?e',
      '?i',
      '?hu',
      '?ren',
      '?b',
      '?zo',
      '?f',
      '?pe',
      '?kan',
      '?y',
      '?non',
      '?pa',
      '?thew',
      '?le',
      '?c',
      '?ran',
      '?ze',
      '?na',
      '?fan',
      '?chae',
      '?che',
      '?le',
      '?ri',
      '?lli',
      '?ga',
      '?nu',
      '?lo',
      '?le',
      '?war',
      '?nan',
      '?bo',
      '?b',
      '?ca',
      '?lu',
      '?vy',
      '?ha',
      '?le',
      '?ko',
      '?gus',
      '?xi',
      '?S',
      '?co',
      '?ji',
      '?hu',
      '?do',
      '?ga',
      '?to',
      '?de',
      '?can',
      '?than',
      '?la',
      '?e',
      '?ch',
      '?ta',
      '?se',
      '?ce',
      '?tia',
      '?do',
      '?ve',
      '?chi',
      '?net',
      '?ckly',
      '?o',
      '?ny',
      '?m',
      '?kie',
      '?wi',
      '?va',
      '?ya',
      '?na',
      '?ne',
      '?nin',
      '?an',
      '?son',
      '?pau',
      '?byn',
      '?mi',
      '?co',
      '?f',
      '?l',
      '?yo',
      '?e',
      '?ni',
      '?xan',
      '?tri',
      '?ba',
      '?b',
      '?si',
      '?pa',
      '?ther',
      '?ku',
      '?tes',
      '?con',
      '?liam',
      '?f',
      '?mi',
      '?joh',
      '?dam',
      '?pe',
      '?ter',
      '?d',
      '?th',
      '?an',
      '?si',
      '?lon',
      '?go',
      '?da',
      '?za',
      '?to',
      '?ra',
      '?by',
      '?na',
      '?ti',
      '?mo',
      '?ne',
      '?vin',
      '?fae',
      '?bam',
      '?s',
      '?fon',
      '?ge',
      '?wan',
      '?an',
      '?quen',
      '?min',
      '?i',
      '?shei',
      '?p',
      '?man',
      '?ran',
      '?ben',
      '?ju',
      '?ri',
      '?ja',
      '?line',
      '?go',
      '?be',
      '?cha',
      '?co',
      '?gan',
      '?g',
      '?san',
      '?me',
      '?ri',
      '?than',
      '?ne',
      '?o',
      '?sha',
      '?b',
      '?bi',
      '?tine',
      '?ha',
      '?to',
      '?wa',
      '?shu',
      '?rge',
      '?g',
      '?fa',
      '?po',
      '?tay',
      '?sa',
      '?lo',
      '?pa',
      '?pe',
      '?he',
      '?nie',
      '?wen',
      '?re',
      '?e',
      '?t',
      '?ma',
      '?per',
      '?zanna',
      '?je',
      '?rl',
      '?ri',
      '?ki',
      '?ki',
      '?ri',
      '?re',
      '?e',
      '?lu',
      '?va',
      '?gan',
      '?ten',
      '?yu',
      '?den',
      '?li',
      '?den',
      '?beth',
      '?pie',
      '?ga',
      '?rist',
      '?po',
      '?bi',
      '?fo',
      '?co',
      '?sus',
      '?mu',
      '?ba',
      '?da',
      '?co',
      '?my',
      '?so',
      '?jo',
      '?na',
      '?ve',
      '?lot',
      '?ty',
      '?ham',
      '?ro',
      '?me',
      '?um',
      '?tri',
      '?han',
      '?co',
      '?le',
      '?nai',
      '?je',
      '?sha',
      '?ken',
      '?shu',
      '?lian',
      '?e',
      '?pea',
      '?ve',
      '?phine',
      '?ba',
      '?so',
      '?in',
      '?fan',
      '?mo',
      '?mo',
      '?si',
      '?ho',
      '?ri',
      '?sha',
      '?mo',
      '?ri',
      '?le',
      '?phi',
      '?ro',
      '?sa',
      '?ti',
      '?mon',
      '?len',
      '?ge',
      '?cha',
      '?re',
      '?wi',
      '?ce',
      '?tan',
      '?ja',
      '?no',
      '?che',
      '?mu',
      '?rol',
      '?be',
      '?fe',
      '?ja',
      '?se',
      '?her',
      '?lu',
      '?cin',
      '?da',
      '?di',
      '?son',
      '?dou',
      '?dun',
      '?ban',
      '?chior',
      '?shau',
      '?do',
      '?ri',
      '?kim',
      '?men',
      '?ran',
      '?a',
      '?ron',
      '?a',
      '?hu',
      '?sha',
      '?wen',
      '?ly',
      '?ho',
      '?sia',
      '?ru',
      '?we',
      '?ton',
      '?phy',
      '?ma',
      '?ru',
      '?bo',
      '?ma',
      '?me',
      '?d',
      '?car',
      '?tha'
    ].forEach(function (e) {
      var k = e.charAt(0);
      var cont = e.substring(1);
      if (k in obj) {
        obj[k] += '/' + cont;
      } else {
        obj[k] = cont;
      }
    });
    return obj;
  }) (),
  selectlonger: function (eng) {
    var ret = '';
    var ls = eng.split('/');
    ls.forEach(function (e) {
      if (e.length > 1) {
        ret = e;
        return;
      }
    });
    return ret || ls[0];
  },
  trans: function (chi) {
    var news = '';
    var tmp;
    for (var i = 0; i < chi.length; i++) {
      if (i == chi.length - 1 && chi[i] == '?') {
        tmp = 'a';
      }
      else tmp = (this.data[chi[i]] || chi[i]).split('/') [0];
      if (i == 0) {
        if (tmp.length == 1) {
          tmp = this.selectlonger(this.data[chi[i]]);
        }
      }
      news += tmp;
    }
    return news;
  },
  alliseng: function (chi) {
    for (var i = 0; i < chi.length; i++) {
      if (!(chi[i] in this.data)) {
        return false;
      }
    }
    return true;
  }
}
phrasetree = {
  data: {
  },
  getmean: function (word) {
    var firstchar = this.data[word.charAt(0)];
    if (firstchar == null) return '';
    if (word in firstchar)
    return firstchar[word];
     else {
      return '';
    }
  },
  setmean: function (word, mean) {
    if (mean.indexOf('=') < 0) return;
    this.data[word[0]] = this.data[word[0]] || {
      maxleng: 0
    };
    this.data[word[0]][word] = mean;
    if (this.data[word[0]].maxleng < word.length) {
      this.data[word[0]].maxleng = word.length;
    }
  },
  save: function () {
    if (store.getItem('useofflinevietphrase') == 'true') {
      if (!window.indexedDB) {
        return alert('Trình duy?t c?a b?n không h? tr? file vietphrase riêng.');
      }
      if (ngdb == null) {
        ngdb = new IdbKvStore('vietphrase');
      }
      ngdb.set('vietphrasedata', phrasetree.data);
    } else
    store.setItem('vietphrase', JSON.stringify(this.data));
  },
  load: function () {
    console.time('loadvp');
    if (true || store.getItem('isloadsingword') == 'true') {
      if (store.getItem('useofflinevietphrase') == 'true') {
        window.priorvp = true;
        window.attachedvp = false;
        if (store.getItem('trans-win') == 'true') {
        } else {
          window.open('http://giangthe.com/html/transwin.htm');
        }
        setTimeout(function () {
          if (window.attachedvp == false) {
            store.setItem('trans-win', 'false');
          }
        }, 30000);
      } else {
        try {
          this.data = JSON.parse(store.getItem('vietphrase')) || {
          };
        } catch (ed) {
        }
      }
    } else
    this.loadsingword();
    console.timeEnd('loadvp');
    phrasetree.setmean('? · ', 'chân · =Chân · ');
    phrasetree.setmean('?? A ?', 'Doraemon=Doraemon');
    phrasetree.setmean(' T ?', ' T Shirt= T Shirt');
    phrasetree.setmean(' U ?', ' USB= USB');
    phrasetree.setmean(' B ?', ' Bilibili= Bilibili');
    phrasetree.setmean('?', '=');
  },
  loadsingword: function () {
    return;
    var http = new XMLHttpRequest();
    var url = '/singword.txt';
    http.open('GET', url, true);
    http.overrideMimeType('text/plain; charset=utf-8');
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.onreadystatechange = function () {
      if (http.readyState == 4 && http.status == 200) {
        this.responseText.split('\n').forEach(function (e) {
          phrasetree.setmean(e[0], e.substring(1));
        });
        phrasetree.save();
        phrasetree.data = JSON.parse(store.getItem('vietphrase')) || {
        };
        store.setItem('isloadsingword', 'true');
      }
    }
    http.send();
  }
}
nametree = {
  data: {
  },
  getmean: function (word) {
    var firstchar = this.data[word.charAt(0)];
    if (firstchar == null) return '';
    if (word in firstchar)
    return firstchar[word];
     else {
      return '';
    }
  },
  setmean: function (word, mean) {
    this.data[word[0]] = this.data[word[0]] || {
      maxleng: 0
    };
    this.data[word[0]][word] = mean;
    if (this.data[word[0]].maxleng < word.length) {
      this.data[word[0]].maxleng = word.length;
    }
  },
  save: function () {
    var curl = document.getElementById('hiddenid').innerHTML.split(';');
    var book = curl[0];
    var chapter = curl[1];
    var host = curl[2];
    store.setItem(host + book + 'v3', JSON.stringify(this.data));
  },
  load: function () {
    var curl = document.getElementById('hiddenid').innerHTML.split(';');
    var book = curl[0];
    var chapter = curl[1];
    var host = curl[2];
    this.data = JSON.parse(store.getItem(host + book + 'v3')) || {
    };
  }
}
function loadnodedata(txt) {
  var node = q('#' + contentcontainer + ' i') [0];
  while (node != null) {
    node.setAttribute('v', node.innerHTML);
    node.innerHTML = node.innerHTML.split('/') [0];
    node = node.nE();
  }
}
window.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
window.IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.msIDBTransaction || {
  READ_WRITE: 'readwrite'
};
window.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange || window.msIDBKeyRange;
(function (e) {
  if (typeof exports === 'object' && typeof module !== 'undefined') {
    module.exports = e()
  } else if (typeof define === 'function' && define.amd) {
    define([], e)
  } else {
    var t;
    if (typeof window !== 'undefined') {
      t = window
    } else if (typeof global !== 'undefined') {
      t = global
    } else if (typeof self !== 'undefined') {
      t = self
    } else {
      t = this
    }
    t.IdbKvStore = e()
  }
}) (function () {
  var e,
  t,
  r;
  return function () {
    function l(o, s, u) {
      function a(r, e) {
        if (!s[r]) {
          if (!o[r]) {
            var t = 'function' == typeof require && require;
            if (!e && t) return t(r, !0);
            if (f) return f(r, !0);
            var n = new Error('Cannot find module \'' + r + '\'');
            throw n.code = 'MODULE_NOT_FOUND',
            n
          }
          var i = s[r] = {
            exports: {
            }
          };
          o[r][0].call(i.exports, function (e) {
            var t = o[r][1][e];
            return a(t || e)
          }, i, i.exports, l, o, s, u)
        }
        return s[r].exports
      }
      for (var f = 'function' == typeof require && require, e = 0; e < u.length; e++) a(u[e]);
      return a
    }
    return l
  }() ({
    1: [
      function (e, t, r) {
        var a = Object.create || E;
        var u = Object.keys || j;
        var o = Function.prototype.bind || k;
        function n() {
          if (!this._events || !Object.prototype.hasOwnProperty.call(this, '_events')) {
            this._events = a(null);
            this._eventsCount = 0
          }
          this._maxListeners = this._maxListeners || undefined
        }
        t.exports = n;
        n.EventEmitter = n;
        n.prototype._events = undefined;
        n.prototype._maxListeners = undefined;
        var i = 10;
        var s;
        try {
          var f = {
          };
          if (Object.defineProperty) Object.defineProperty(f, 'x', {
            value: 0
          });
          s = f.x === 0
        } catch (e) {
          s = false
        }
        if (s) {
          Object.defineProperty(n, 'defaultMaxListeners', {
            enumerable: true,
            get: function () {
              return i
            },
            set: function (e) {
              if (typeof e !== 'number' || e < 0 || e !== e) throw new TypeError('"defaultMaxListeners" must be a positive number');
              i = e
            }
          })
        } else {
          n.defaultMaxListeners = i
        }
        n.prototype.setMaxListeners = function e(t) {
          if (typeof t !== 'number' || t < 0 || isNaN(t)) throw new TypeError('"n" argument must be a positive number');
          this._maxListeners = t;
          return this
        };
        function l(e) {
          if (e._maxListeners === undefined) return n.defaultMaxListeners;
          return e._maxListeners
        }
        n.prototype.getMaxListeners = function e() {
          return l(this)
        };
        function c(e, t, r) {
          if (t) e.call(r);
           else {
            var n = e.length;
            var i = b(e, n);
            for (var o = 0; o < n; ++o) i[o].call(r)
          }
        }
        function h(e, t, r, n) {
          if (t) e.call(r, n);
           else {
            var i = e.length;
            var o = b(e, i);
            for (var s = 0; s < i; ++s) o[s].call(r, n)
          }
        }
        function p(e, t, r, n, i) {
          if (t) e.call(r, n, i);
           else {
            var o = e.length;
            var s = b(e, o);
            for (var u = 0; u < o; ++u) s[u].call(r, n, i)
          }
        }
        function v(e, t, r, n, i, o) {
          if (t) e.call(r, n, i, o);
           else {
            var s = e.length;
            var u = b(e, s);
            for (var a = 0; a < s; ++a) u[a].call(r, n, i, o)
          }
        }
        function d(e, t, r, n) {
          if (t) e.apply(r, n);
           else {
            var i = e.length;
            var o = b(e, i);
            for (var s = 0; s < i; ++s) o[s].apply(r, n)
          }
        }
        n.prototype.emit = function e(t) {
          var r,
          n,
          i,
          o,
          s,
          u;
          var a = t === 'error';
          u = this._events;
          if (u) a = a && u.error == null;
           else if (!a) return false;
          if (a) {
            if (arguments.length > 1) r = arguments[1];
            if (r instanceof Error) {
              throw r
            } else {
              var f = new Error('Unhandled "error" event. (' + r + ')');
              f.context = r;
              throw f
            }
            return false
          }
          n = u[t];
          if (!n) return false;
          var l = typeof n === 'function';
          i = arguments.length;
          switch (i) {
            case 1:
              c(n, l, this);
              break;
            case 2:
              h(n, l, this, arguments[1]);
              break;
            case 3:
              p(n, l, this, arguments[1], arguments[2]);
              break;
            case 4:
              v(n, l, this, arguments[1], arguments[2], arguments[3]);
              break;
            default:
              o = new Array(i - 1);
              for (s = 1; s < i; s++) o[s - 1] = arguments[s];
              d(n, l, this, o)
          }
          return true
        };
        function y(e, t, r, n) {
          var i;
          var o;
          var s;
          if (typeof r !== 'function') throw new TypeError('"listener" argument must be a function');
          o = e._events;
          if (!o) {
            o = e._events = a(null);
            e._eventsCount = 0
          } else {
            if (o.newListener) {
              e.emit('newListener', t, r.listener ? r.listener : r);
              o = e._events
            }
            s = o[t]
          }
          if (!s) {
            s = o[t] = r;
            ++e._eventsCount
          } else {
            if (typeof s === 'function') {
              s = o[t] = n ? [
                r,
                s
              ] : [
                s,
                r
              ]
            } else {
              if (n) {
                s.unshift(r)
              } else {
                s.push(r)
              }
            }
            if (!s.warned) {
              i = l(e);
              if (i && i > 0 && s.length > i) {
                s.warned = true;
                var u = new Error('Possible EventEmitter memory leak detected. ' + s.length + ' "' + String(t) + '" listeners ' + 'added. Use emitter.setMaxListeners() to ' + 'increase limit.');
                u.name = 'MaxListenersExceededWarning';
                u.emitter = e;
                u.type = t;
                u.count = s.length;
                if (typeof console === 'object' && console.warn) {
                  console.warn('%s: %s', u.name, u.message)
                }
              }
            }
          }
          return e
        }
        n.prototype.addListener = function e(t, r) {
          return y(this, t, r, false)
        };
        n.prototype.on = n.prototype.addListener;
        n.prototype.prependListener = function e(t, r) {
          return y(this, t, r, true)
        };
        function _() {
          if (!this.fired) {
            this.target.removeListener(this.type, this.wrapFn);
            this.fired = true;
            switch (arguments.length) {
              case 0:
                return this.listener.call(this.target);
              case 1:
                return this.listener.call(this.target, arguments[0]);
              case 2:
                return this.listener.call(this.target, arguments[0], arguments[1]);
              case 3:
                return this.listener.call(this.target, arguments[0], arguments[1], arguments[2]);
              default:
                var e = new Array(arguments.length);
                for (var t = 0; t < e.length; ++t) e[t] = arguments[t];
                this.listener.apply(this.target, e)
            }
          }
        }
        function m(e, t, r) {
          var n = {
            fired: false,
            wrapFn: undefined,
            target: e,
            type: t,
            listener: r
          };
          var i = o.call(_, n);
          i.listener = r;
          n.wrapFn = i;
          return i
        }
        n.prototype.once = function e(t, r) {
          if (typeof r !== 'function') throw new TypeError('"listener" argument must be a function');
          this.on(t, m(this, t, r));
          return this
        };
        n.prototype.prependOnceListener = function e(t, r) {
          if (typeof r !== 'function') throw new TypeError('"listener" argument must be a function');
          this.prependListener(t, m(this, t, r));
          return this
        };
        n.prototype.removeListener = function e(t, r) {
          var n,
          i,
          o,
          s,
          u;
          if (typeof r !== 'function') throw new TypeError('"listener" argument must be a function');
          i = this._events;
          if (!i) return this;
          n = i[t];
          if (!n) return this;
          if (n === r || n.listener === r) {
            if (--this._eventsCount === 0) this._events = a(null);
             else {
              delete i[t];
              if (i.removeListener) this.emit('removeListener', t, n.listener || r)
            }
          } else if (typeof n !== 'function') {
            o = - 1;
            for (s = n.length - 1; s >= 0; s--) {
              if (n[s] === r || n[s].listener === r) {
                u = n[s].listener;
                o = s;
                break
              }
            }
            if (o < 0) return this;
            if (o === 0) n.shift();
             else g(n, o);
            if (n.length === 1) i[t] = n[0];
            if (i.removeListener) this.emit('removeListener', t, u || r)
          }
          return this
        };
        n.prototype.removeAllListeners = function e(t) {
          var r,
          n,
          i;
          n = this._events;
          if (!n) return this;
          if (!n.removeListener) {
            if (arguments.length === 0) {
              this._events = a(null);
              this._eventsCount = 0
            } else if (n[t]) {
              if (--this._eventsCount === 0) this._events = a(null);
               else delete n[t]
            }
            return this
          }
          if (arguments.length === 0) {
            var o = u(n);
            var s;
            for (i = 0; i < o.length; ++i) {
              s = o[i];
              if (s === 'removeListener') continue;
              this.removeAllListeners(s)
            }
            this.removeAllListeners('removeListener');
            this._events = a(null);
            this._eventsCount = 0;
            return this
          }
          r = n[t];
          if (typeof r === 'function') {
            this.removeListener(t, r)
          } else if (r) {
            for (i = r.length - 1; i >= 0; i--) {
              this.removeListener(t, r[i])
            }
          }
          return this
        };
        n.prototype.listeners = function e(t) {
          var r;
          var n;
          var i = this._events;
          if (!i) n = [
          ];
           else {
            r = i[t];
            if (!r) n = [
            ];
             else if (typeof r === 'function') n = [
              r.listener || r
            ];
             else n = L(r)
          }
          return n
        };
        n.listenerCount = function (e, t) {
          if (typeof e.listenerCount === 'function') {
            return e.listenerCount(t)
          } else {
            return w.call(e, t)
          }
        };
        n.prototype.listenerCount = w;
        function w(e) {
          var t = this._events;
          if (t) {
            var r = t[e];
            if (typeof r === 'function') {
              return 1
            } else if (r) {
              return r.length
            }
          }
          return 0
        }
        n.prototype.eventNames = function e() {
          return this._eventsCount > 0 ? Reflect.ownKeys(this._events) : [
          ]
        };
        function g(e, t) {
          for (var r = t, n = r + 1, i = e.length; n < i; r += 1, n += 1) e[r] = e[n];
          e.pop()
        }
        function b(e, t) {
          var r = new Array(t);
          for (var n = 0; n < t; ++n) r[n] = e[n];
          return r
        }
        function L(e) {
          var t = new Array(e.length);
          for (var r = 0; r < t.length; ++r) {
            t[r] = e[r].listener || e[r]
          }
          return t
        }
        function E(e) {
          var t = function () {
          };
          t.prototype = e;
          return new t
        }
        function j(e) {
          var t = [
          ];
          for (var r in e)
          if (Object.prototype.hasOwnProperty.call(e, r)) {
            t.push(r)
          }
          return r
        }
        function k(e) {
          var t = this;
          return function () {
            return t.apply(e, arguments)
          }
        }
      },
      {
      }
    ],
    2: [
      function (e, t, r) {
        if (typeof Object.create === 'function') {
          t.exports = function e(t, r) {
            t.super_ = r;
            t.prototype = Object.create(r.prototype, {
              constructor: {
                value: t,
                enumerable: false,
                writable: true,
                configurable: true
              }
            })
          }
        } else {
          t.exports = function e(t, r) {
            t.super_ = r;
            var n = function () {
            };
            n.prototype = r.prototype;
            t.prototype = new n;
            t.prototype.constructor = t
          }
        }
      },
      {
      }
    ],
    3: [
      function (e, t, r) {
        t.exports = n;
        function n(r) {
          var n;
          var i;
          var o;
          if (r != null && typeof r !== 'function') throw new Error('cb must be a function');
          if (r == null && typeof Promise !== 'undefined') {
            n = new Promise(function (e, t) {
              i = e;
              o = t
            })
          }
          function e(e, t) {
            if (n) {
              if (e) o(e);
               else i(t)
            } else {
              if (r) r(e, t);
               else if (e) throw e
            }
          }
          e.promise = n;
          return e
        }
      },
      {
      }
    ],
    '/': [
      function (e, t, r) {
        t.exports = y;
        var p = e('events').EventEmitter;
        var n = e('inherits');
        var f = e('promisize');
        var v = typeof window === 'undefined' ? self : window;
        var d = v.indexedDB || v.mozIndexedDB || v.webkitIndexedDB || v.msIndexedDB;
        y.INDEXEDDB_SUPPORT = d != null;
        y.BROADCAST_SUPPORT = v.BroadcastChannel != null;
        n(y, p);
        function y(e, t, r) {
          var n = this;
          if (typeof e !== 'string') throw new Error('A name must be supplied of type string');
          if (!d) throw new Error('IndexedDB not supported');
          if (typeof t === 'function') return new y(e, null, t);
          if (!(n instanceof y)) return new y(e, t, r);
          if (!t) t = {
          };
          p.call(n);
          n._db = null;
          n._closed = false;
          n._channel = null;
          n._waiters = [
          ];
          var i = t.channel || v.BroadcastChannel;
          if (i) {
            n._channel = new i(e);
            n._channel.onmessage = h
          }
          var o = d.open(e);
          o.onerror = s;
          o.onsuccess = a;
          o.onupgradeneeded = f;
          n.on('newListener', c);
          function s(e) {
            _(e);
            n._close(e.target.error);
            if (r) r(e.target.error)
          }
          function u(e) {
            _(e);
            n._close(e.target.error)
          }
          function a(e) {
            if (n._closed) {
              e.target.result.close()
            } else {
              n._db = e.target.result;
              n._db.onclose = l;
              n._db.onerror = u;
              for (var t in n._waiters) try {
                n._waiters[t]._init(null);
              } catch (uxi) {
              }
              n._waiters = null;
              if (r) r(null);
              n.emit('open')
            }
          }
          function f(e) {
            var t = e.target.result;
            t.createObjectStore('kv', {
              autoIncrement: true
            })
          }
          function l() {
            n._close()
          }
          function c(e) {
            if (e !== 'add' && e !== 'set' && e !== 'remove') return;
            if (!n._channel) return n.emit('error', new Error('No BroadcastChannel support'))
          }
          function h(e) {
            if (e.data.method === 'add') n.emit('add', e.data);
             else if (e.data.method === 'set') n.emit('set', e.data);
             else if (e.data.method === 'remove') n.emit('remove', e.data)
          }
        }
        y.prototype.get = function (e, t) {
          return this.transaction('readonly').get(e, t)
        };
        y.prototype.getMultiple = function (e, t) {
          return this.transaction('readonly').getMultiple(e, t)
        };
        y.prototype.set = function (e, t, r) {
          r = f(r);
          var n = null;
          var i = this.transaction('readwrite', function (e) {
            n = n || e;
            r(n)
          });
          i.set(e, t, function (e) {
            n = e
          });
          return r.promise
        };
        y.prototype.json = function (e, t) {
          return this.transaction('readonly').json(e, t)
        };
        y.prototype.keys = function (e, t) {
          return this.transaction('readonly').keys(e, t)
        };
        y.prototype.values = function (e, t) {
          return this.transaction('readonly').values(e, t)
        };
        y.prototype.remove = function (e, t) {
          t = f(t);
          var r = null;
          var n = this.transaction('readwrite', function (e) {
            r = r || e;
            t(r)
          });
          n.remove(e, function (e) {
            r = e
          });
          return t.promise
        };
        y.prototype.clear = function (t) {
          t = f(t);
          var r = null;
          var e = this.transaction('readwrite', function (e) {
            r = r || e;
            t(r)
          });
          e.clear(function (e) {
            r = e
          });
          return t.promise
        };
        y.prototype.count = function (e, t) {
          return this.transaction('readonly').count(e, t)
        };
        y.prototype.add = function (e, t, r) {
          r = f(r);
          var n = null;
          var i = this.transaction('readwrite', function (e) {
            n = n || e;
            r(n)
          });
          i.add(e, t, function (e) {
            n = e
          });
          return r.promise
        };
        y.prototype.iterator = function (e, t) {
          return this.transaction('readonly').iterator(e, t)
        };
        y.prototype.transaction = function (e, t) {
          if (this._closed) throw new Error('Database is closed');
          var r = new i(this, e, t);
          if (this._db) r._init(null);
           else this._waiters.push(r);
          return r
        };
        y.prototype.close = function () {
          this._close()
        };
        y.prototype._close = function (e) {
          if (this._closed) return;
          this._closed = true;
          if (this._db) this._db.close();
          if (this._channel) this._channel.close();
          this._db = null;
          this._channel = null;
          if (e) this.emit('error', e);
          this.emit('close');
          for (var t in this._waiters) this._waiters[t]._init(e || new Error('Database is closed'));
          this._waiters = null;
          this.removeAllListeners()
        };
        function i(e, t, r) {
          if (typeof t === 'function') return new i(e, null, t);
          this._kvStore = e;
          this._mode = t || 'readwrite';
          this._objectStore = null;
          this._waiters = null;
          this.finished = false;
          this.onfinish = f(r);
          this.done = this.onfinish.promise;
          if (this._mode !== 'readonly' && this._mode !== 'readwrite') {
            throw new Error('mode must be either "readonly" or "readwrite"')
          }
        }
        i.prototype._init = function (e) {
          var t = this;
          if (t.finished) return;
          if (e) return t._close(e);
          var r = t._kvStore._db.transaction('kv', t._mode);
          r.oncomplete = i;
          r.onerror = o;
          r.onabort = o;
          t._objectStore = r.objectStore('kv');
          for (var n in t._waiters) t._waiters[n](null, t._objectStore);
          t._waiters = null;
          function i() {
            t._close(null)
          }
          function o(e) {
            _(e);
            t._close(e.target.error)
          }
        };
        i.prototype._getObjectStore = function (e) {
          if (this.finished) throw new Error('Transaction is finished');
          if (this._objectStore) return e(null, this._objectStore);
          this._waiters = this._waiters || [
          ];
          this._waiters.push(e)
        };
        i.prototype.set = function (n, i, o) {
          var s = this;
          if (n == null || i == null) throw new Error('A key and value must be given');
          o = f(o);
          s._getObjectStore(function (e, t) {
            if (e) return o(e);
            try {
              var r = t.put(i, n)
            } catch (e) {
              return o(e)
            }
            r.onerror = _.bind(this, o);
            r.onsuccess = function () {
              if (s._kvStore._channel) {
                s._kvStore._channel.postMessage({
                  method: 'set',
                  key: n,
                  value: i
                })
              }
              o(null)
            }
          });
          return o.promise
        };
        i.prototype.add = function (n, i, o) {
          var s = this;
          if (i == null && n != null) return s.add(undefined, n, o);
          if (typeof i === 'function' || i == null && o == null) return s.add(undefined, n, i);
          if (i == null) throw new Error('A value must be provided as an argument');
          o = f(o);
          s._getObjectStore(function (e, t) {
            if (e) return o(e);
            try {
              var r = n == null ? t.add(i) : t.add(i, n)
            } catch (e) {
              return o(e)
            }
            r.onerror = _.bind(this, o);
            r.onsuccess = function () {
              if (s._kvStore._channel) {
                s._kvStore._channel.postMessage({
                  method: 'add',
                  key: n,
                  value: i
                })
              }
              o(null)
            }
          });
          return o.promise
        };
        i.prototype.get = function (n, i) {
          var e = this;
          if (n == null) throw new Error('A key must be given as an argument');
          i = f(i);
          e._getObjectStore(function (e, t) {
            if (e) return i(e);
            try {
              var r = t.get(n)
            } catch (e) {
              return i(e)
            }
            r.onerror = _.bind(this, i);
            r.onsuccess = function (e) {
              i(null, e.target.result)
            }
          });
          return i.promise
        };
        i.prototype.getMultiple = function (u, a) {
          var e = this;
          if (u == null) throw new Error('An array of keys must be given as an argument');
          a = f(a);
          if (u.length === 0) {
            a(null, [
            ]);
            return a.promise
          }
          e._getObjectStore(function (e, t) {
            if (e) return a(e);
            var n = u.slice().sort();
            var i = 0;
            var o = {
            };
            var s = function () {
              return u.map(function (e) {
                return o[e]
              })
            };
            var r = t.openCursor();
            r.onerror = _.bind(this, a);
            r.onsuccess = function (e) {
              var t = e.target.result;
              if (!t) {
                a(null, s());
                return
              }
              var r = t.key;
              while (r > n[i]) {
                ++i;
                if (i === n.length) {
                  a(null, s());
                  return
                }
              }
              if (r === n[i]) {
                o[r] = t.value;
                t.continue()
              } else {
                t.continue(n[i])
              }
            }
          });
          return a.promise
        };
        i.prototype.json = function (e, r) {
          var t = this;
          if (typeof e === 'function') return t.json(null, e);
          r = f(r);
          var n = {
          };
          t.iterator(e, function (e, t) {
            if (e) return r(e);
            if (t) {
              n[t.key] = t.value;
              t.continue()
            } else {
              r(null, n)
            }
          });
          return r.promise
        };
        i.prototype.keys = function (e, r) {
          var t = this;
          if (typeof e === 'function') return t.keys(null, e);
          r = f(r);
          var n = [
          ];
          t.iterator(e, function (e, t) {
            if (e) return r(e);
            if (t) {
              n.push(t.key);
              t.continue()
            } else {
              r(null, n)
            }
          });
          return r.promise
        };
        i.prototype.values = function (e, r) {
          var t = this;
          if (typeof e === 'function') return t.values(null, e);
          r = f(r);
          var n = [
          ];
          t.iterator(e, function (e, t) {
            if (e) return r(e);
            if (t) {
              n.push(t.value);
              t.continue()
            } else {
              r(null, n)
            }
          });
          return r.promise
        };
        i.prototype.remove = function (n, i) {
          var o = this;
          if (n == null) throw new Error('A key must be given as an argument');
          i = f(i);
          o._getObjectStore(function (e, t) {
            if (e) return i(e);
            try {
              var r = t.delete(n)
            } catch (e) {
              return i(e)
            }
            r.onerror = _.bind(this, i);
            r.onsuccess = function () {
              if (o._kvStore._channel) {
                o._kvStore._channel.postMessage({
                  method: 'remove',
                  key: n
                })
              }
              i(null)
            }
          });
          return i.promise
        };
        i.prototype.clear = function (n) {
          var e = this;
          n = f(n);
          e._getObjectStore(function (e, t) {
            if (e) return n(e);
            try {
              var r = t.clear()
            } catch (e) {
              return n(e)
            }
            r.onerror = _.bind(this, n);
            r.onsuccess = function () {
              n(null)
            }
          });
          return n.promise
        };
        i.prototype.count = function (n, i) {
          var e = this;
          if (typeof n === 'function') return e.count(null, n);
          i = f(i);
          e._getObjectStore(function (e, t) {
            if (e) return i(e);
            try {
              var r = n == null ? t.count() : t.count(n)
            } catch (e) {
              return i(e)
            }
            r.onerror = _.bind(this, i);
            r.onsuccess = function (e) {
              i(null, e.target.result)
            }
          });
          return i.promise
        };
        i.prototype.iterator = function (n, i) {
          var e = this;
          if (typeof n === 'function') return e.iterator(null, n);
          if (typeof i !== 'function') throw new Error('A function must be given');
          e._getObjectStore(function (e, t) {
            if (e) return i(e);
            try {
              var r = n == null ? t.openCursor() : t.openCursor(n)
            } catch (e) {
              return i(e)
            }
            r.onerror = _.bind(this, i);
            r.onsuccess = function (e) {
              var t = e.target.result;
              i(null, t)
            }
          })
        };
        i.prototype.abort = function () {
          if (this.finished) throw new Error('Transaction is finished');
          if (this._objectStore) this._objectStore.transaction.abort();
          this._close(new Error('Transaction aborted'))
        };
        i.prototype._close = function (e) {
          if (this.finished) return;
          this.finished = true;
          this._kvStore = null;
          this._objectStore = null;
          for (var t in this._waiters) this._waiters[t](e || new Error('Transaction is finished'));
          this._waiters = null;
          if (this.onfinish) this.onfinish(e);
          this.onfinish = null
        };
        function _(e, t) {
          if (t == null) return _(null, e);
          t.preventDefault();
          t.stopPropagation();
          if (e) e(t.target.error)
        }
      },
      {
        events: 1,
        inherits: 2,
        promisize: 3
      }
    ]
  }, {
  }, [
  ]) ('/')
});
var ngdb;
function loadVietphraseOffline(cb) {
  if (!window.indexedDB) {
    return alert('Trình duy?t c?a b?n không h? tr? file vietphrase riêng.');
  }
  if (ngdb == null) {
    ngdb = new IdbKvStore('vietphrase', {
    }, loadVietphraseOffline);
  } else {
    ngdb.get('vietphrasedata', function (err, val) {
      if (err) throw err;
      phrasetree.data = val;
      if (window.loadvp === false) {
        window.loadvp = true;
      }
    });
  }
}
function insertVietphraseOffline(file) {
  if (!window.indexedDB) {
    return alert('Trình duy?t c?a b?n không h? tr? file vietphrase riêng.');
  }
  if (ngdb == null) {
    ngdb = new IdbKvStore('vietphrase');
  }
  var fr = new FileReader();
  fr.onload = function (e)
  {
    var lines = fr.result.split(/\r?\n/);
    var count = 0;
    for (var i = 0; i < lines.length; i++) {
      var phr = lines[i].split('=');
      if (phr.length > 1) {
        phrasetree.setmean(phr[0], '=' + phr[1]);
        count++;
      }
    }
    ngdb.set('vietphrasedata', phrasetree.data);
    store.setItem('useofflinevietphrase', 'true');
    window.priorvp = true;
    alert('Nh?p thành công ' + count + ' dòng.');
  };
  fr.readAsText(file);
}
function openinsertvpmodal() {
  var md = createModal('Nh?p vietphrase cá nhân');
  md.body().innerHTML = '<br><input type="file" id="vpfile" onch="insertVietphraseOffline(this.files[0])"><br><center><button class="btn" onclick="insertVietphraseOffline(g(\'vpfile\').files[0])">Nh?p</button></center><br><div id="insertvpstatus"></div>';
  md.show();
}
function toonemeaning(mulmean) {
  return mulmean.split(/[\/\|]/) [0];
}
function convertchitovi(chinese) {
  chinese = standardizeinput(chinese);
  var stringBuilder = [
  ];
  var num = chinese.length - 1;
  var lastword = {
    data: ''
  };
  var i = 0;
  while (i <= num)
  {
    var flag = false;
    for (var j = 12; j > 0; j--)
    {
      if (chinese.length >= i + j)
      {
        var cn = chinese.substr(i, j);
        var text = phrasetree.getmean(cn);
        if (text != '' && text.length > 0)
        {
          text = text.substring(1);
          lastlen = j;
          appendTranslatedWord(stringBuilder, '<i h=\'\'t=\'' + cn + '\'v=\'' + text + '\'>' + toonemeaning(text) + '</i>', lastword);
          flag = true;
          i += j;
          break;
        }
      }
    }
    if (!flag)
    {
      var han = convertohanviet(chinese[i]);
      appendTranslatedWord(stringBuilder, '<i h=\'' + han + '\'t=\'' + chinese[i] + '\'>' + han + '</i>', lastword);
      i++;
    }
  }
  return stringBuilder.join('');
}
function convertohanviet(chi) {
  return hanvietdic[chi] || '';
}
function convertohanviets(str) {
  var result = [
  ];
  for (var i = 0; i < str.length; i++) {
    result.push(convertohanviet(str[i]));
  }
  return result.join(' ');
}
function appendTranslatedWord(result, translatedText, lastTranslatedWord)
{
  if (/(\. |\“|\'|\? |\! |\.\” |\?\” |\!\” |\: )$/.test(lastTranslatedWord.data))
  {
    lastTranslatedWord.data = appendUcFirst(translatedText);
  }
  else if (/[ \(]$/.test(lastTranslatedWord.data))
  {
    lastTranslatedWord.data = translatedText;
  }
  else
  {
    lastTranslatedWord.data = ' ' + translatedText;
  }
  result.push(lastTranslatedWord.data);
}
function appendUcFirst(text)
{
  var result;
  if (!text)
  {
    result = text;
  }
  else if (text[0] == '[' && 2 <= text.length)
  {
    result = '[' + text[1].toUpperCase() + ((text.length <= 2) ? '' : text.substring(2));
  }
  else
  {
    result = text[0].toUpperCase() + ((text.length <= 1) ? '' : text.substring(1));
  }
  return result;
}
function standardizeinput(original)
{
  var result;
  if (!original)
  {
    result = '';
  }
  else
  {
    var text = original;
    var array = [
      '“',
      ',',
      '?',
      ':',
      '”',
      '?',
      '!',
      '.',
      '?',
      '…'
    ];
    var array2 = [
      ' “',
      ', ',
      '.',
      ': ',
      '” ',
      '?',
      '!',
      '.',
      ', ',
      '...'
    ];
    for (var i = 0; i < array.length; i++)
    {
      text = text.replace(new RegExp(array[i], 'g'), array2[i]);
    }
    text = text.replace(/  /g, ' ').replace(/ \r\n/g, '\n').replace(/ \n/g, '\n');
    return text;
  }
  return result;
}
function override(funcName, newFunc) {
  if (!window.overrideglobal) window.overrideglobal = {
  };
  if (window[funcName]) {
    window.overrideglobal[funcName] = window[funcName];
    window[funcName] = function () {
      var _super = window.overrideglobal[funcName];
      newFunc(argument);
    }
  }
}
var speaker = {
  utter: false,
  parsed: false,
  senid: 0,
  sentences: [
  ],
  senmap: [
  ],
  hnrgx: /[!“”]/,
  loadedconfig: false,
  speaking: false,
  parseSen: function () {
    var startnd = g(contentcontainer).childNodes[0];
    var allsens = [
    ];
    var sen = [
    ];
    var minsen = [
    ];
    var stack = '';
    while (startnd != null) {
      if (startnd.tagName == 'BR') {
        if (sen.length > 0) {
          allsens.push(sen);
          sen = [
          ];
        }
      } else
      if (startnd.tagName == 'I') {
        sen.push(startnd);
      } else
      if (startnd.nodeType == document.TEXT_NODE) {
        if (startnd.textContent.contain('“')) {
          if (sen.length > 0) {
            allsens.push(sen);
            sen = [
            ];
          }
          sen.push(startnd);
        } else if (startnd.textContent.contain('”')) {
          sen.push(startnd);
          allsens.push(sen);
          sen = [
          ];
        } else if (startnd.textContent.contain(',')) {
          sen.push(startnd);
          allsens.push(sen);
          sen = [
          ];
        } else if (startnd.textContent.contain('.')) {
          sen.push(startnd);
          allsens.push(sen);
          sen = [
          ];
        } else {
          sen.push(startnd);
        }
      }
      startnd = startnd.nextSibling;
    }
    if (sen.length > 0) {
      allsens.push(sen);
    }
    this.sentences = allsens;
    parsed = true;
    for (var i = 0; i < allsens.length; i++) {
      var tx = this.senToText(i);
      this.senmap.push({
        text: tx.trim().replace(/[“,”\.]/g, ''),
        type: this.getSenType3(tx)
      });
    }
    for (var i = 0; i < allsens.length; i++) {
      if (this.senmap[i].type == 'vo') {
        for (var co = 0; co < 15; co++) {
          if (i + co >= allsens.length) {
            break;
          }
          if (this.senmap[i + co].type != 've') {
            this.senmap[i + co].type = 'hn';
          } else {
            this.senmap[i + co].type = 'hn';
            break;
          }
        }
      }
    }
  },
  getSenType3: function (text) {
    var text;
    if (text.contain('“') && text.contain('”')) {
      return 'hn';
    }
    if (text.contain('“')) {
      return 'vo';
    }
    if (text.contain('!') && text.contain('?')) {
      return 'hs';
    }
    if (text.contain('”')) {
      return 've';
    }
    return 'nn';
  },
  getSenType2: function (sen) {
    var text;
    for (var i = 0; i < sen.length; i++) {
      text = sen[i].textContent;
      if (text.contain('“') && text.contain('”')) {
        return 'hn';
      }
      if (text.contain('“')) {
        return 'vo';
      }
      if (text.contain('!') && text.contain('?')) {
        return 'hs';
      }
      if (text.contain('”')) {
        return 've';
      }
    }
    return 'nn';
  },
  getSenType: function (text) {
    if (text.contain('!') && text.contain('?')) {
      return 'hs';
    }
    if (this.hnrgx.test(text)) {
      return 'hn';
    }
    return 'nn';
  },
  senToText: function (senid) {
    var text = '';
    var sen = this.sentences[senid];
    for (var i = 0; i < sen.length; i++) {
      if (sen[i].tagName == 'I') {
        if (sen[i].gT().length > 0) text += ' ' + sen[i].textContent;
      } else
      text += ' ' + sen[i].textContent;
    }
    return text;
  },
  loadconfig: function (iswaiter) {
    if (!this.utter) {
      if (!window.speechSynthesis) {
        return alert('Thi?t b? c?a b?n không h? tr? nghe sách.');
      }
      var voices = window.speechSynthesis.getVoices();
      var isVietnamese = false;
      for (var i = 0; i < voices.length; i++) {
        if (voices[i].lang == 'vi-VN') {
          isVietnamese = true;
          break;
        }
      }
      if (!isVietnamese) {
        if (iswaiter) {
          alert('Chua cài d?t ti?ng vi?t, nghe sách ch? h? tr? thi?t b? android, truy c?p cài d?t gi?ng nói trên thi?t b? và t?i ti?ng vi?t.');
          return;
        } else {
          setTimeout(function () {
            speaker.loadconfig(true);
          }, 2000);
        }
      }
      this.utter = new SpeechSynthesisUtterance();
      this.utter.lang = 'vi-VN';
      this.utter.onend = function () {
        speaker.readnext();
      }
      if (store.getItem('speaker-flex') == 'false') {
        this.flexread = false;
      }
      if (store.getItem('speaker-spd')) {
        this.utter.rate = 0 + store.getItem('speaker-spd');
      }
      if (store.getItem('speaker-pit')) {
        this.utter.pitch = 0 + store.getItem('speaker-pit');
      }
    }
  },
  readBook: function () {
    if (this.speaking) {
      this.showsetting();
      return;
    }
    this.loadconfig();
    if (!this.parsed) {
      this.parseSen();
    }
    this.senid = - 1;
    this.readnext();
    this.speaking = true;
  },
  setPitch(type) {
    if (this.flexread == false) {
      return;
    }
    if (type == 'hs') {
      this.utter.pitch = 1.2;
      this.utter.rate = 0.7;
    }
    if (type == 'hn') {
      this.utter.pitch = 1.2;
      this.utter.rate = 1;
    }
    if (type == 'nn') {
      this.utter.pitch = 0.8;
      this.utter.rate = 1;
    }
  },
  readSen: function (senid) {
    var s = this.senToText(this.senid);
    if (!this.utter) {
      this.loadconfig();
    }
    this.utter.text = s[0];
    this.setPitch(s[1]);
    this.speak();
  },
  highlightOff: function (id) {
    if (id < 0) return;
    var s = this.sentences[id];
    for (var i = 0; i < s.length; i++) {
      if (s[i].tagName == 'I') {
        s[i].style.color = 'inherit';
      }
    }
  },
  highlightOn: function (id) {
    if (id < 0) return;
    var s = this.sentences[id];
    if (s != null)
    for (var i = 0; i < s.length; i++) {
      if (s[i].tagName == 'I') {
        s[i].style.color = 'red';
      }
    }
  },
  readnext: function () {
    this.highlightOff(this.senid);
    this.senid++;
    this.highlightOn(this.senid);
    var s = this.senmap[this.senid];
    if (!this.utter) {
      this.loadconfig();
    }
    this.utter.text = this.nextSenText || s.text;
    if (this.senid < this.sentences.length) {
      convertSenWithGG(this.sentences[this.senid]);
    }
    this.setPitch(s.type);
    this.after(100);
  },
  after: function (time) {
    setTimeout(function () {
      speaker.speak();
    }, time);
  },
  speak: function () {
    speechSynthesis.speak(this.utter);
  },
  showsetting: function () {
    this.loadconfig();
    var wd = ui.win.create('Cài d?t nghe sách');
    var rw = wd.body.row();
    rw.addText('Âm di?u nh?p nhàng');
    var tg = rw.addToggle(function () {
      store.setItem('speaker-flex', this.checked.toString());
      speaker.flexread = this.checked;
    });
    tg.checked = true;
    if (store.getItem('speaker-flex') == 'false') {
      tg.checked = false;
    }
    rw = wd.body.row();
    rw.addText('T?c d? d?c: &nbsp;&nbsp;');
    rw.addButton('Ch?m hon', 'speaker.decSpd()', 'green');
    var ip = rw.addInput('ip-speakerspd', 'T?c d?');
    ip.value = this.utter.rate;
    rw.addButton('Nhanh hon', 'speaker.incSpd()', 'green');
    rw = wd.body.row();
    rw.addText('Cao d? d?c: &nbsp;&nbsp;');
    rw.addButton('Th?p hon', 'speaker.decPit()', 'green');
    var ip = rw.addInput('ip-speakerpit', 'Cao d?');
    ip.value = this.utter.rate;
    rw.addButton('Cao hon', 'speaker.incPit()', 'green');
    rw = wd.body.row('');
    rw.addButton('T?m ngung', 'speechSynthesis.pause()', 'blue w-50');
    rw.addButton('Ti?p t?c d?c', 'speechSynthesis.resume()', 'green w-50');
    wd.show();
  },
  decPit: function () {
    this.utter.pitch -= 0.1;
    store.setItem('speaker-pit', this.utter.pitch);
    try {
      g('ip-speakerpit').value = this.utter.pitch;
    } catch (e) {
    };
  },
  incPit: function () {
    this.utter.pitch += 0.1;
    store.setItem('speaker-pit', this.utter.pitch);
    try {
      g('ip-speakerpit').value = this.utter.pitch;
    } catch (e) {
    };
  },
  decSpd: function () {
    this.utter.rate -= 0.1;
    store.setItem('speaker-spd', this.utter.rate);
    try {
      g('ip-speakerspd').value = this.utter.rate;
    } catch (e) {
    };
  },
  incSpd: function () {
    this.utter.rate += 0.1;
    store.setItem('speaker-spd', this.utter.rate);
    try {
      g('ip-speakerspd').value = this.utter.rate;
    } catch (e) {
    };
  }
}
var ntsengine = {
  tim: 0,
  wordConnector: function (node) {
    if (node.nE() != null && node.isspace(true)) {
      var l = [
        node,
        node.nE()
      ];
      if (l[1].nE() != null && l[1].isspace(true)) {
        l.push(l[1].nE());
      }
      if (l.length > 2) {
        this.containWord(l, function (d) {
          l[0].style.border = '1px solid green';
          l[0].style.borderWidth = '1px 0 1px 1px';
          l[2].style.border = '1px solid green';
          l[2].style.borderWidth = '1px 1px 1px 0px';
          if (l[2].nE() != null) {
            ntsengine.wordConnector(l[2].nE());
          }
        }, function (d) {
          l.pop();
          ntsengine.containWord(l, function (d) {
            l[0].style.border = '1px solid green';
            l[0].style.borderWidth = '1px 0 1px 1px';
            l[1].style.border = '1px solid green';
            l[1].style.borderWidth = '1px 1px 1px 0px';
            if (l[1].nE().nE() != null) {
              ntsengine.wordConnector(l[1].nE().nE());
            }
          }, function (d) {
            ntsengine.wordConnector(l[1]);
          });
        });
      }
      else {
        this.containWord(l, function (d) {
          l[0].style.border = '1px solid green';
          l[0].style.borderWidth = '1px 0 1px 1px';
          l[1].style.border = '1px solid green';
          l[1].style.borderWidth = '1px 1px 1px 0px';
          if (l[1].nE() != null) {
            ntsengine.wordConnector(l[1].nE());
          }
        }, function (d) {
          ntsengine.wordConnector(l[1]);
        });
      }
    } else if (node.nE()) {
      this.wordConnector(node.nE());
    } else {
      console.timeEnd('nts');
    }
  },
  containWord: function (wl, t, f) {
    tse.send('005', wl.sumChinese(''), function () {
      if (this.down != 'false') {
        t(this.down);
      } else {
        f(this.down);
      }
    });
  },
  retrans: function () {
    var nd = q('#' + contentcontainer + ' i') [0];
    console.time('nts');
    this.wordConnector(nd);
  }
}
function overread() {
}
function clearWhiteSpace() {
  var empty = q('#' + contentcontainer + ' i:empty');
  for (var i = 0; i < empty.length; i++) {
    if (!empty[i].isspace(true) && empty[i].isspace(false)) {
      empty[i].previousSibling.textContent = '';
      empty[i].previousSibling.isspacehidden = true;
      var crn = empty[i];
      while (crn.pE() && crn.pE().textContent == '' && crn.pE().isspace(false)) {
        crn = crn.pE();
        crn.previousSibling.textContent = '';
        crn.previousSibling.isspacehidden = true;
        if (crn.previousSibling.previousSibling.nodeType == 3) {
          crn.previousSibling.previousSibling.textContent = '';
          crn.previousSibling.previousSibling.isspacehidden = true;
        }
      }
    }
  }
  clearDiLastSen();
}
function clearDiLastSen() {
  q('#' + contentcontainer + ' i[t="?"],#' + contentcontainer + ' i[t="?"]').forEach(function (e) {
    if (e.isspace(false) && (!e.isspace(true) || e.nE().textContent == '')) {
      e.previousSibling.textContent = '';
      e.previousSibling.isspacehidden = true;
    }
  });
}
function nodeIsHan(node) {
  if (node.textContent == '') return false;
  var m = node.getAttribute('v');
  if (m == null) {
    return false;
  }
  m = m.split('/');
  var percent = 0;
  var h = node.gH().toLowerCase().split(' ');
  var l = node.textContent.toLowerCase().split(' ');
  if (l.length < 2) {
    return false;
  }
  for (var j = 0; j < m.length; j++) {
    l = m[j].toLowerCase().split(' ');
    for (var i = 0; i < l.length; i++) {
      if (h.indexOf(l[i]) < 0)
      {
        percent++;
        break;
      }
    }
  }
  if (percent > m.length / 3) {
    return false;
  }
  return true;
}
function toCnWithName() {
  q('#' + contentcontainer + ' i').forEach(function (e) {
    if (!e.containName() && !nodeIsHan(e)) {
      e.textContent = e.cn;
    } else {
      e.textContent = '' + e.textContent + '';
    }
  });
  var a = g(contentcontainer);
  ui.copy(a.innerText);
}
function modvp() {
  phrasetree.setmean(g('modifyvpboxip1').value, g('modifyvpboxip2').value + '=' + g('modifyvpboxip3').value);
  phrasetree.save();
  hideNb();
  replaceVietphrase();
  $('#modifyvpbox').hide();
  syncvpfile('update');
}
function movemeaning() {
  var mean = g('modifyvpboxip3').value;
  if (mean.indexOf('/') >= 0) {
    var idx = mean.indexOf('/');
    mean = mean.substring(idx + 1) + '/' + mean.substring(0, idx);
    g('modifyvpboxip3').value = mean;
  }
}
function movehantomean() {
  g('modifyvpboxip3').value = g('modifyvpboxip2').value;
}
function delvp() {
  if (window.priorvp) {
    var vptodel = g('modifyvpboxip1').value;
    if (phrasetree.data[vptodel[0]][vptodel]) {
      delete phrasetree.data[vptodel[0]][vptodel];
      phrasetree.save();
    }
  } else {
    var vptodel = g('modifyvpboxip1').value;
    if (vptodel[0] in phrasetree.data && vptodel in phrasetree.data[vptodel[0]]) {
      delete phrasetree.data[vptodel[0]][vptodel];
      phrasetree.save();
      location.reload();
    } else {
    }
  }
}
function deleteName() {
  var nametodel = g('addnameboxip1').value;
  namew.value = namew.value.replace(new RegExp('\n\\$' + nametodel + '=.*?m', 'g'), '\n');
  saveNS();
}
function copychinese() {
  var copyText = g('zw');
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand('copy');
}
function googletrans(a) {
  googletranslate(g(a).value);
}
function googlesearch(a) {
  var win = window.open('https://www.google.com/search?q=' + g(a).value, '_blank');
  win.focus();
}
function instrans(e) {
  if (phrasetree.getmean(e.value) != '') {
    g('instrans').value = phrasetree.getmean(e.value).split('=') [1];
  }
  else
  tse.send('001', e.value, function () {
    g('instrans').value = this.down;
  });
}
function isNameLv2() {
  return (window.setting && (window.setting.allownamev3 || window.setting.allownamev2));
}
function instrans2(e, isfirst) {
  tse.send('001', e.value, function () {
    var da = this.down.split('/');
    var selec = g('addnameboxip2').previousElementSibling;
    selec.innerHTML = '';
    var sox;
    da.forEach(function (e) {
      sox = document.createElement('option');
      sox.setAttribute('value', e.trim());
      sox.innerHTML = e.trim();
      selec.appendChild(sox);
    });
    g('addnameboxip2').value = selec.children[0].value;
  });
  tse.send('004', e.value, function () {
    g('addnameboxip3').value = this.down.split('=') [0].replace(/ +/g, ' ').trim();
  });
  googletranslate(e.value, function (d) {
    g('addnameboxip4').value = d;
  });
  ajax('ajax=getnamefromdb&name=' + encodeURIComponent(e.value.trim()), function (down) {
    var da = down.split('/');
    var selec = g('addnameboxip5').previousElementSibling;
    selec.innerHTML = '';
    var sox;
    da.forEach(function (e) {
      sox = document.createElement('option');
      sox.setAttribute('value', e.trim());
      sox.innerHTML = e.trim();
      selec.appendChild(sox);
    });
    g('addnameboxip5').value = selec.children[0].value;
  });
}
function instrans3(e, isfirst) {
  tse.send('004', e.value, function () {
    g('modifyvpboxip2').value = this.down.split('=') [0].replace(/ +/g, ' ').trim();
  });
  if (phrasetree.getmean(e.value) != '') {
    g('modifyvpboxip3').value = phrasetree.getmean(e.value).split('=') [1];
  }
  else {
    if (isfirst) {
      g('modifyvpboxip3').value = g('instrans').value;
    }
    tse.send('001', e.value, function () {
      g('modifyvpboxip3').value = this.down.trim();
    });
  }
}
var isfirsttimeopennamebox = true;
var isbookmanager = false;
function showAddName() {
  g('addnameboxip1').value = i5.value;
  g('addnameboxip3').value = i2.value;
  instrans2(g('addnameboxip1'), true);
  $('#addnamebox').show();
  if (isfirsttimeopennamebox) {
    isfirsttimeopennamebox = false;
    checkIsManager();
  }
  if (isbookmanager) {
    g('booknamemanager').removeAttribute('hidden');
    if (store.getItem('issavetobook') == 'true') {
      g('issavetobook').checked = true;
    }
  }
}
function checkIsManager() {
  ajax('ajax=defaultbooknaming&sub=ismanager&host=' + bookhost + '&bookid=' + bookid, function (d) {
    if (d == 'true') {
      isbookmanager = true;
      g('booknamemanager').removeAttribute('hidden');
      if (store.getItem('issavetobook') == 'true') {
        g('issavetobook').checked = true;
      }
    }
  });
}
function addNameToBook(name) {
  if (g('issavetobook').checked) {
    ajax('ajax=defaultbooknaming&sub=addname&bookid=' + bookid
    + '&host=' + bookhost
    + '&name=' + encodeURIComponent(name), function () {
    });
  }
}
function addSuperName(type, flag) {
  var phr = '';
  var nname = '';
  if (type == 'el') {
    nname = '$' + g('addnameboxip1').value + '=' + g('addnameboxip4').value;
  } else if (type == 'hv') {
    if (flag == 'a') {
      nname = '$' + g('addnameboxip1').value + '=' + titleCase(g('addnameboxip3').value);
    } else if (flag == 'z') {
      nname = '$' + g('addnameboxip1').value + '=' + g('addnameboxip3').value;
    } else if (flag == 'f') {
      phr = g('addnameboxip3').value;
      phr = phr.replace(phr.charAt(0), titleCase(phr.charAt(0)));
      nname = '$' + g('addnameboxip1').value + '=' + phr;
    } else if (flag == 'l') {
      nname = '$' + g('addnameboxip1').value + '=' + lowerNLastWord(titleCase(g('addnameboxip3').value), 1);
    }
    else if (flag == 's') {
      nname = '$' + g('addnameboxip1').value + '=' + lowerNLastWord(titleCase(g('addnameboxip3').value), g('addnameboxip1').value.length - 2);
    }
  } else if (type == 'vp') {
    nname = '$' + g('addnameboxip1').value + '=' + g('addnameboxip2').value;
  }
  else if (type == 'kn') {
    nname = '$' + g('addnameboxip1').value + '=' + g('addnameboxip5').value;
  }
  namew.value = nname + '\n' + namew.value;
  addNameToBook(nname);
  $('#addnamebox').hide();
  saveNS();
  excute();
}
function openmodvp() {
  instrans3(g('zw'), true);
  g('modifyvpboxip1').value = g('zw').value;
  g('modifyvpboxip2').value = i2.value;
  $('#modifyvpbox').show();
}
function convertSenWithGG(s) {
  return false;
}
function convertSenWithGG2(node) {
  var namedb = {
  };
  var genname = function (n) {
    var name = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);
    var name = n;
    if (!(name in namedb)) {
      namedb[name] = n;
      return name;
    } else {
      return genname(n);
    }
  }
  var nd = node;
  var sen = [
    nd
  ];
  while (nd.isspace(true)) {
    nd = nd.nE();
    sen.push(nd);
  }
  nd = node;
  while (nd.isspace(false)) {
    nd = nd.pE();
    sen.unshift(nd);
  }
  var t = '';
  for (var i = 0; i < sen.length; i++) {
    if (sen[i].nodeType == 3) {
      t += sen[i].textContent;
    } else
    if (sen[i].containName()) {
      t += ' ' + genname(sen[i].textContent) + ' ';
    } else {
      var m = sen[i].getAttribute('v');
      var m2 = false;
      if (m) {
        m = m.toLowerCase().split('/');
        var h = sen[i].gH();
        var hc = 0;
        for (var c = 0; c < m.length; c++) {
          if (h == m[c]) {
            hc++;
          }
        }
        m2 = hc / m.length > 0.4;
      }
      if (m2) {
        t += ' ' + genname(sen[i].textContent) + ' ';
      } else
      t += sen[i].gT();
    }
  }
  googletranslatevi(t, function (s) {
    for (var n in namedb) {
      var r = new RegExp(n, 'gi');
      s = s.replace(r, namedb[n]);
    }
    mergeWord(sen);
    sen[0].textContent = s;
  });
  console.log(t);
  return t;
}
function googletranslatevi(chi, callb) {
  var http = new XMLHttpRequest();
  var url = 'https://translate.googleapis.com/translate_a/single?client=gtx&text=&sl=zh-CN&tl=vi&dt=t&q=' + encodeURI(chi);
  http.open('GET', url, true);
  http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  http.onreadystatechange = function () {
    if (http.readyState == 4 && http.status == 200) {
      callb(JSON.parse(this.responseText) [0][0][0]);
    }
  }
  http.send();
}
function TFCoreTranslate(node) {
  var text = '';
  var c = g(contentcontainer).childNodes;
  var namemap = {
  };
  function toSlug(str) {
    str = str.toLowerCase();
    str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    str = str.replace(/[dÐ]/g, 'd');
    str = str.replace(/([^0-9a-z-\s])/g, '');
    str = str.replace(/(\s+)/g, '');
    return str;
  }
  function mapName(name, chi) {
    var sname = toSlug(name);
    sname = titleCase(sname);
    namemap[sname] = chi;
    return sname;
  }
  for (var i = 0; i < c.length; i++) {
    if (c[i].tagName == 'BR') {
      text += '\n';
    }
    if (c[i].nodeType == 3) {
      text += c[i].textContent.trim();
    }
    if (c[i].tagName == 'I') {
      if (c[i].containName()) {
        text += mapName(c[i].textContent, c[i].gT());
      } else
      text += c[i].gT();
    }
  }
  console.log(namemap);
  TFInit.fromString(text, function (e) {
    e.transform();
    var tfm = e.getText();
    for (var name in namemap) {
      var r = new RegExp(name, 'gi');
      tfm = tfm.replace(r, namemap[name]);
    }
    ajax('ajax=trans&content=' + encodeURIComponent(tfm), function (e) {
      g(contentcontainer).innerHTML = preprocess(e.substring(1));
      applyNodeList();
      excute();
    });
  });
}
