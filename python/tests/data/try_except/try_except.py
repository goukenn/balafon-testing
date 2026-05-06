

try:
    raise TypeError('missing type')
except:
    print('finally raise ')

print('sample')

def unraise():
    print('unraise')