from random import randint

def put_x_y(grid, empty, x, y, points):
    if grid[x][y] != empty:
        return False
    points.append((x, y))
    return True

def get_ocean(grid, empty, radius, center):
    x_center = center[0]
    y_center = center[1]
    points = []
    if not put_x_y(grid, empty, x_center, y_center, points):
        return False, False
    while radius > 0:
        x = 0
        y = radius
        d = radius - 1
        while y >= x:
            if not put_x_y(grid, empty, x_center + x, y_center + y, points):
                return False, False
            if not put_x_y(grid, empty, x_center + y, y_center + x, points):
                return False, False
            if not put_x_y(grid, empty, x_center - x, y_center + y, points):
                return False, False
            if not put_x_y(grid, empty, x_center - y, y_center + x, points):
                return False, False
            if not put_x_y(grid, empty, x_center + x, y_center - y, points):
                return False, False
            if not put_x_y(grid, empty, x_center + y, y_center - x, points):
                return False, False
            if not put_x_y(grid, empty, x_center - x, y_center - y, points):
                return False, False
            if not put_x_y(grid, empty, x_center - y, y_center - x, points):
                return False, False
            if d >= 2 * x:
                d = d - 2 * x - 1
                x = x + 1
            elif d < 2 * (radius - y):
                d = d + 2 * y - 1
                y = y - 1
            else:
                d = d + 2 * (y - x - 1)
                y = y - 1
                x = x + 1
        radius -= 1
    return True, points

def draw_ocean(grid, water, points):
    for (x, y) in points:
        grid[x][y] = water

def gen_ocean(grid, empty, water, radius):
    """
    grid: ...
    empty: caractere case vide
    water: caractere pour remplir case
    radius: ...

    return: True si a pu dessiner, False sinon
    """
    valid = []
    max_x  = len(grid)
    max_y = len(grid[0])
    for x in range(radius - 1, max_x - radius + 1):
        for y in range(radius - 1, max_y - radius + 1):
            valid.append((x, y))
    for i in range(len(valid) - 1, 0, -1):
            j = randint(0, i)
            temp = valid[i]
            valid[i] = valid[j]
            valid[j] = temp
    for i in range(0, len(valid)):
        free, points = get_ocean(grid, empty, radius - 1, valid[i])
        if free:
            draw_ocean(grid, water, points)
            return True
    return False

def print_map(grid):
    for line in grid:
        print (line)

empty = '.'
water = '0'
size = 30
grid = [[empty] * size for _ in range(size)]
while True:
    if not gen_ocean(grid, empty, water, 5):
        break
print_map(grid)

# def get_valid_coord(grid, size):
#     x_pos = randint(size * 2, 60 - size * 2)
#     y_pos = randint(size * 2, 60 - size * 2)
#     y = y_pos - ((size * 2) + 10)
#     if y < 0:
#         y = 0
#     while y <= y_pos + ((size * 2) + 10) and y < 60 - size * 2:
#         x = x_pos - ((size * 2) + 10)
#         if (x < 0):
#             x = 0 
#         while x <= x_pos + ((size * 2) + 10) and x < 60 - size * 2:
#             if (grid[y][x] != "."):
#                 return get_valid_coord(grid, size)
#             x += 1
#         y = y + 1
#     return [x_pos, y_pos]

# def add_border(grid, x_pos, y_pos, size, i):
#     current_x = x_pos - (size - i)
#     current_y = y_pos - size - i
#     while current_x <= x_pos + (size - i):
#         grid[current_y][current_x] = "W"
#         current_x += 1
#     current_x = x_pos - (size - i)
#     current_y = y_pos + size + i
#     while current_x <= x_pos + (size - i):
#         grid[current_y][current_x] = "W"
#         current_x += 1
#     current_x = x_pos - (size + i)
#     current_y = y_pos - (size - i)
#     while current_y <= y_pos + (size - i):
#         grid[current_y][current_x] = "W"
#         current_y += 1
#     current_x = x_pos + (size + i)
#     current_y = y_pos - (size - i)
#     while current_y <= y_pos + (size - i):
#         grid[current_y][current_x] = "W"
#         current_y += 1

# def gen_ocean(grid, size):
#     coord = get_valid_coord(grid, size)
#     x_pos = coord[0]
#     y_pos = coord[1]
#     final_size = size * 2
#     print ("At coord : [" + str(x_pos) + "/" + str(y_pos) + "]")
#     if (grid[y_pos][x_pos] == "."):
#         print ("empty")
#     else:
#         print("filled")
#     current_x = x_pos - size
#     current_y = y_pos - size
#     final_x = x_pos + size
#     final_y = y_pos + size
#     while current_x <= final_x and current_y <= final_y:
#         grid[current_y][current_x] = "W"
#         current_x = current_x + 1
#         if (current_x > final_x):
#             current_x = x_pos - size
#             current_y = current_y + 1
#     i = 1
#     while i <= size:
#         add_border(grid, x_pos, y_pos, size, i)
#         i += 1
