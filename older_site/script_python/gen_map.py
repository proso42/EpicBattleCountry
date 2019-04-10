from random import randint
from math import *

def get_valid_coord(grid, size):
    x_pos = randint(size * 2, 60 - size * 2)
    y_pos = randint(size * 2, 60 - size * 2)
    y = y_pos - ((size * 2) + 10)
    if y < 0:
        y = 0
    while y <= y_pos + ((size * 2) + 10) and y < 60 - size * 2:
        x = x_pos - ((size * 2) + 10)
        if (x < 0):
            x = 0 
        while x <= x_pos + ((size * 2) + 10) and x < 60 - size * 2:
            if (grid[y][x] != "."):
                return get_valid_coord(grid, size)
            x += 1
        y = y + 1
    return [x_pos, y_pos]

def add_border(grid, x_pos, y_pos, size, i):
    current_x = x_pos - (size - i)
    current_y = y_pos - size - i
    while current_x <= x_pos + (size - i):
        grid[current_y][current_x] = "W"
        current_x += 1
    current_x = x_pos - (size - i)
    current_y = y_pos + size + i
    while current_x <= x_pos + (size - i):
        grid[current_y][current_x] = "W"
        current_x += 1
    current_x = x_pos - (size + i)
    current_y = y_pos - (size - i)
    while current_y <= y_pos + (size - i):
        grid[current_y][current_x] = "W"
        current_y += 1
    current_x = x_pos + (size + i)
    current_y = y_pos - (size - i)
    while current_y <= y_pos + (size - i):
        grid[current_y][current_x] = "W"
        current_y += 1

def gen_ocean(grid, size):
    coord = get_valid_coord(grid, size)
    x_pos = coord[0]
    y_pos = coord[1]
    final_size = size * 2
    print ("At coord : [" + str(x_pos) + "/" + str(y_pos) + "]")
    if (grid[y_pos][x_pos] == "."):
        print ("empty")
    else:
        print("filled")
    current_x = x_pos - size
    current_y = y_pos - size
    final_x = x_pos + size
    final_y = y_pos + size
    while current_x <= final_x and current_y <= final_y:
        grid[current_y][current_x] = "W"
        current_x = current_x + 1
        if (current_x > final_x):
            current_x = x_pos - size
            current_y = current_y + 1
    i = 1
    while i <= size:
        add_border(grid, x_pos, y_pos, size, i)
        i += 1
    

def print_map(grid):
    for line in grid:
        print (line)

grid = [["."] * 60 for _ in range(60)]
gen_ocean(grid, 4)
gen_ocean(grid, 3)
print_map(grid)


